<?php

namespace Fakturaservice\Edelivery;

use DateInterval;
use DateTime;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use mysqli;
use SimpleXMLElement;
use Fakturaservice\Edelivery\{
    util\LoggerInterface,
    util\Logger,
    NemLookUpCli,
    OIOUBL\NetworkType
};


class OxalisCli
{

    private LoggerInterface $_log;
    private string $_url;
    private string $_userName;
    private string $_passWord;
    private ?mysqli $_oxalisDB;
    private DateTime $_today;
    private string $_cliId;
    private array $_errors;
    private NemLookUpCli $_lookUpCli;

    /**
     * @throws Exception
     */
    public function __construct(string $url, string $userName, string $passWord, LoggerInterface $logger, string $clientId = null)
    {
        $this->_today       = new DateTime();
        $this->_url         = $url;
        $this->_userName    = $userName;
        $this->_passWord    = $passWord;
        $this->_cliId       = $clientId ?? basename(str_replace('\\', '/', get_called_class()));
        $this->_log         = $logger;
        $this->_log->setChannel($this->_cliId);
        $this->_errors      = [];
        $this->_oxalisDB    = null;
        $this->_lookUpCli   = new NemLookUpCli(clone($this->_log));
    }

    public function success(): bool
    {
        return empty($this->_errors);
    }
    public function getErrorMsg($includeCode=true) : string
    {
        $errorMsgString = "";
        foreach ($this->_errors as $code => $msg)
        {
            $errorMsgString .= ($includeCode?"$code ":"") . "{$this->extractErrorMessage($msg)}\n";
        }
        return $errorMsgString;
    }
    private function extractErrorMessage($xmlString): string
    {
        $xml = @simplexml_load_string($xmlString);
        if($xml === false)
            return $xmlString;

        $xml->registerXPathNamespace('ns', 'http://www.erst.dk/oxalis/api');

        // Check if 'errorMessage' exists
        $errorMessage = $xml->xpath('//ns:errorMessage');
        if (!empty($errorMessage)) {
            return (string) $errorMessage[0];
        }

        // Check if 'message' exists
        $message = $xml->xpath('//ns:message');
        if (!empty($message)) {
            return (string) $message[0];
        }

        return "";
    }
    public function getErrorArr() : array
    {
        return $this->_errors;
    }
    public function resetErrors(): void
    {
        $this->_errors      = [];
    }

    public function registerAccountReceiver(string $participantId, string $ledgersId): bool
    {
        if(!isset($this->_oxalisDB))
        {
            $this->_log->log("OXALIS DB is NOT configured", Logger::LV_1, Logger::LOG_ERR);
            return false;
        }

        $query  = "INSERT ";
        $query  .= "INTO ";
        $query  .= "oxalis_db.Account_Receiver ";
        $query  .= "(";
        $query  .= "    account_id, ";
        $query  .= "    participant_id, ";
        $query  .= "    version, ";
        $query  .= "    created_date, ";
        $query  .= "    created_by";
        $query  .= ")";
        $query  .= "VALUES ";
        $query  .= "(";
        $query  .= "    1, ";
        $query  .= "    '$participantId', ";
        $query  .= "    0, ";
        $query  .= "    '" . $this->_today->format("Y-m-d\TH:i:s\+01:00") . "', ";
        $query  .= "    '$ledgersId'";
        $query  .= ");";

        return (bool)$this->_oxalisDB->query($query);
    }
    public function getAccountReceiver(string $participantId): array
    {
        $r = [];
        if(!isset($this->_oxalisDB))
        {
            $this->_log->log("OXALIS DB is NOT configured", Logger::LV_1, Logger::LOG_ERR);
            return $r;
        }

        $selectQuery  = "SELECT ";
        $selectQuery  .= "  * ";
        $selectQuery  .= "FROM ";
        $selectQuery  .= "  Account_Receiver ";
        $selectQuery  .= "WHERE ";
        $selectQuery  .= "  participant_id='$participantId'";
        $selectQuery  .= ";";

        $res    = $this->_oxalisDB->query($selectQuery);
        if($res->num_rows > 0)
            $r      = $res->fetch_assoc();
        return $r;
    }
    public function unregisterAccountReceiver(string $participantId): bool
    {
        if(!isset($this->_oxalisDB))
        {
            $this->_log->log("OXALIS DB is NOT configured", Logger::LV_1, Logger::LOG_ERR);
            return false;
        }

        $deleteQuery  = "DELETE ";
        $deleteQuery  .= "FROM ";
        $deleteQuery  .= "  Account_Receiver ";
        $deleteQuery  .= "WHERE ";
        $deleteQuery  .= "  participant_id='$participantId';";

        return  $this->_oxalisDB->query($deleteQuery);
    }

    /**
     * @throws Exception
     */
    public function outbox(string $xml, $networkTypeId=NetworkType::NemHandel_AS4): string
    {
        if(!$this->isWrappedInStandardBusinessDocument($xml))
        {
            $oxalisWrapper  = new OxalisWrapper($xml, new Logger($this->_log->getLogLevel()));
            $xml            = $oxalisWrapper->wrapSBD($networkTypeId);
        }
        $httpCode = 999;//Non-existing http return code in case curl fails
        $this->_log->log("Sending document");
        $res = $this->post($xml, "outbox", $httpCode);
        $this->_log->log("Response received:\n\n$res\n");
        return $this->handleResponse($res, $httpCode);
    }

    public function inbox(string $endpoint=null): array
    {
        if(!isset($this->_oxalisDB))
        {
            $this->_log->log("OXALIS DB is NOT configured", Logger::LV_1, Logger::LOG_ERR);
            return [];
        }

        $eDocuments = [];

        $selectQuery    = "SELECT \n";
        $selectQuery    .= "    msgCont.id, \n";
        $selectQuery    .= "    msg.message_uuid, \n";
        $selectQuery    .= "    msg.receiver, \n";
        $selectQuery    .= "    msgCont.data \n";
        $selectQuery    .= "FROM \n";
        $selectQuery    .= "    oxalis_db.Message_Content AS msgCont \n";
        $selectQuery    .= "LEFT JOIN \n";
        $selectQuery    .= "    oxalis_db.Message AS msg \n";
        $selectQuery    .= "    ON msgCont.id = msg.message_content_id \n";
        $selectQuery    .= "WHERE \n";
        $selectQuery    .= isset($endpoint)?"    msg.receiver LIKE '%$endpoint' AND \n":"";
        $selectQuery    .= "    msg.direction = 'IN' AND \n";
        $selectQuery    .= "    (\n";
        $selectQuery    .= "        (msgCont.updated_by IS NULL) OR \n";
        $selectQuery    .= "        (msgCont.updated_by != '$this->_cliId') \n";
        $selectQuery    .= "    );\n";

        $res = $this->_oxalisDB->query($selectQuery);
        while ($r = $res->fetch_assoc())
        {

            $xml = $this->stripSBD($r["data"]);
            $eDocuments[$r["id"]] = ["UUID" => $r["message_uuid"], "payload" => $xml];
        }
        return $eDocuments;

    }

    public function markIncomingAsRead($id)
    {
        $updateMsgContQuery    = "UPDATE \n";
        $updateMsgContQuery    .= "    oxalis_db.Message_Content t \n";
        $updateMsgContQuery    .= "SET t.updated_by = '$this->_cliId' \n";
        $updateMsgContQuery    .= "WHERE t.id = '$id';\n";

        $this->_oxalisDB->query($updateMsgContQuery);
    }

    public function cleanup($keepNumOfDays): int
    {
        if(!isset($this->_oxalisDB))
        {
            $this->_log->log("OXALIS DB is NOT configured", Logger::LV_1, Logger::LOG_ERR);
            return 0;
        }

        $selectQuery    = "UPDATE \n";
        $selectQuery    .= "    Message_Content \n";
        $selectQuery    .= "SET \n";
        $selectQuery    .= "    data = NULL \n";
        $selectQuery    .= "WHERE \n";
        $selectQuery    .= "    data IS NOT NULL AND \n";
        $selectQuery    .= "    created_date < NOW() - INTERVAL $keepNumOfDays DAY; \n";

        $this->_oxalisDB->query($selectQuery);

        return $this->_oxalisDB->affected_rows;

    }

    public function stripSBD($eDocument)
    {
        $dom = new DomDocument();
        $dom->loadXML($eDocument);

        $xpath = new DomXPath($dom);

        // Register the namespace used in the XML
        $xpath->registerNamespace('ns', 'http://www.unece.org/cefact/namespaces/StandardBusinessDocumentHeader');

        // Use XPath to query and retrieve the value
        $query              = "//ns:DocumentIdentification/ns:Type";
        $documentTypeNode   = $xpath->query($query);

        if ($documentTypeNode->length > 0)
        {
            $documentType = $documentTypeNode->item(0)->nodeValue;
        } else {
            return null;
        }

        // Use XPath to find the <Invoice> or <CreditNote> element regardless of namespace
//        $invoiceNodeList = $xpath->query("//*[local-name()=\"{$documentType}\"");
        $invoiceNodeList = $xpath->query("//*[local-name()=\"$documentType\"]");

        if ($invoiceNodeList->length > 0)
        {
            // Extract the <Invoice> or <CreditNote> node and its child content
            $invoiceNode = $invoiceNodeList->item(0);

            // Create a new DOMDocument to preserve the namespaces
            $resultDoc = new DOMDocument();
            $resultNode = $resultDoc->importNode($invoiceNode, true);

            $resultDoc->appendChild($resultNode);


            // Output the extracted XML with preserved namespaces
            return $resultDoc->saveXML();
        }
        else
        {
            return $eDocument;
        }
    }


    /**
     * @param $sbdDocumentXml
     * @return mixed
     */
    private function getDocumentIdentificationTypeFromSBD($sbdDocumentXml)
    {
        $sbdDocument = new DOMDocument();
        $sbdDocument->loadXML($sbdDocumentXml);

        $xpath          = new DOMXPath($sbdDocument);
        $documentType   = $xpath->query('//StandardBusinessDocumentHeader/DocumentIdentification/Type')->item(0)->textContent;
        $this->_log->log("Found Document type:               $documentType", Logger::LV_2);

        return $documentType;
    }

    private function isWrappedInStandardBusinessDocument($xmlString): bool
    {
        // Create a new DOMDocument
        $doc = new DOMDocument();

        // Load the XML string
        $doc->loadXML($xmlString);

        // Get the root element
        $root = $doc->documentElement;

        // Check if the root element is named 'StandardBusinessDocument'
        if ($root->nodeName === 'StandardBusinessDocument') {
            return true;
        }
        return false;
    }

    private function post(string $xml, string $args, int &$httpCode): string
    {
        $curl = curl_init();

        $auth   = base64_encode("$this->_userName:$this->_passWord");

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->_url}api/$args",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $xml,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/xml",
                "Authorization: Basic $auth"
            ),
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        curl_close($curl);
        return $response;
    }

    /**
     * @throws Exception
     */
    private function handleResponse(string $resXml, $httpCode): string
    {
        $responseDocument   = new DOMDocument();
        if(!$responseDocument->loadXML($resXml))
        {
            $this->_errors[$httpCode]   = $resXml;
            return $httpCode;
        }

        $xpath = new DOMXPath($responseDocument);
        $xpath->registerNamespace('api', 'http://www.erst.dk/oxalis/api');


        // Check if //api:errors exists
        $errorNodes = $xpath->query('//api:errors');
        if ($errorNodes->length > 0)
        {
            // Use XPath to select error codes and messages
            foreach ($errorNodes as $errorNode)
            {
                $codeNode   = $xpath->query('api:code', $errorNode)->item(0);
                $code       = $codeNode?$codeNode->textContent:$xpath->query('api:errorCode', $errorNode)->item(0)->textContent;

                $messageNode    = $xpath->query('api:message', $errorNode)->item(0);
                $message        = $messageNode?$messageNode->textContent:$xpath->query('api:errorMessage', $errorNode)->item(0)->textContent;

                $this->_errors[$code]   = $message;

                $this->_log->log("Error received from Oxalis: $code:'$message'", Logger::LV_1, Logger::LOG_ERR);
            }
            return array_key_last($this->_errors);
        }

        $UUIDNode   = $xpath->query('//api:messageUuid')->item(0);
        return $UUIDNode ? $UUIDNode->textContent : "NO MESSAGE UUID WAS RECEIVED";

    }


    /**
     * @throws Exception
     */
    public function connectOxalisDB($oxalisDBHst, $oxalisDBUsr, $oxalisDBPwd, $oxalisDB): bool
    {
        $this->_log->log("HOST: $oxalisDBHst");
        @$this->_oxalisDB = new mysqli($oxalisDBHst, $oxalisDBUsr, $oxalisDBPwd, $oxalisDB);
        if ($this->_oxalisDB->connect_errno)
        {
            $this->_log->log("Connection to OXALIS DB failed: '$oxalisDBUsr'", Logger::LV_1, Logger::LOG_WARN);
            $this->_log->log($this->_oxalisDB->connect_error, Logger::LV_1, Logger::LOG_ERR);
            $this->_oxalisDB = null;
            return false;
        } else {
            $this->_log->log("Connection to OXALIS DB succeeded: '$oxalisDBUsr'");
            return true;
        }
    }

}