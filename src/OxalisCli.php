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
    private string $_className;
    private array $_errors;
    private NemLookUpCli $_lookUpCli;

    /**
     * @throws Exception
     */
    public function __construct(string $url, string $userName, string $passWord, LoggerInterface $logger)
    {
        $this->_today       = new DateTime();
        $this->_url         = $url;
        $this->_userName    = $userName;
        $this->_passWord    = $passWord;
        $this->_className   = basename(str_replace('\\', '/', get_called_class()));
        $this->_log         = $logger;
        $this->_log->setChannel($this->_className);
        $this->_errors      = [];
        $this->_oxalisDB    = null;
        $this->_lookUpCli   = new NemLookUpCli(clone($this->_log));
    }

    public function success(): bool
    {
        return empty($this->_errors);
    }
    public function getErrorMsg() : string
    {
        $errorMsgString = "";
        foreach ($this->_errors as $code => $msg)
        {
            $errorMsgString .= "$code:$msg\n";
        }
        return $errorMsgString;
    }
    public function getErrorArr() : array
    {
        return $this->_errors;
    }

    public function registerAccountReceiver(string $participantId, string $ledgersId): bool
    {
        if(!isset($this->_oxalisDB))
            return false;

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
            return $r;

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
            return false;

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
        $this->_log->log("Sending document");
        $res = $this->post($xml, "outbox");
        $this->_log->log("Response received:\n\n$res\n");
        if(!$this->success())
            return "";
        return $this->handleResponse($res);
    }

    public function inbox(string $endpoint=null): array
    {
        if(!isset($this->_oxalisDB))
            return [];

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
        $selectQuery    .= "        (msgCont.updated_by != '$this->_className') \n";
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
        $updateMsgContQuery    .= "SET t.updated_by = '$this->_className' \n";
        $updateMsgContQuery    .= "WHERE t.id = '$id';\n";

        $this->_oxalisDB->query($updateMsgContQuery);
    }

    /**
     * @throws Exception
     */
    public function createTSR(string $startDate, string $reporterId):string
    {
        $this->_log->log("Calling createTSR('$startDate')");
        if(!isset($this->_oxalisDB))
            return "";

        $startDate      = new DateTime($startDate);
        $endDate        = clone ($startDate);
        $endDate->add(new DateInterval("P1M"));

        $transactions                   = $this->getTRSTransactions($startDate, $endDate);
        $sortedTransactions             = $this->sortingTSRTransactions($transactions);

        $xml = $this->generateXml($sortedTransactions, $startDate, $endDate, $reporterId);
        $this->_log->log("\n$xml", Logger::LV_2);
        return $xml;
    }
    public function createEUSR($startDate, $reporterCertCN): string
    {
        $this->_log->log("Calling createTSR('$startDate')");
        if(!isset($this->_oxalisDB))
            return "";
        return "";

    }

    private function sortingTSRTransactions($transactions): array
    {
        $SubtotalArrayPerSP_DT_PR       = [];
        $SubtotalArrayPerSP_DT_PR_CC    = [];
        foreach ($transactions as $trans)
        {
            $documentTypeId     = explode("::", $trans["document_type_id"], 2);
            $peppolProcessId    = explode("::", $trans["peppol_process_id"], 2);

            if(($documentTypeId[0] !== NemLookUpCli::BUSINESS_SCOPE_IDENTIFIER) || empty($trans["CN"]))
                continue;

            $keySP_DT_PR_CC = "{$trans["C"]}{$trans["CN"]}{$trans["document_type_id"]}{$trans["peppol_process_id"]}";
            $keySP_DT_PR    = "{$trans["CN"]}{$trans["document_type_id"]}{$trans["peppol_process_id"]}";

            $incomingCountSP_DT_PR_CC  = $SubtotalArrayPerSP_DT_PR_CC[$keySP_DT_PR_CC]["Incoming"]??0;
//            $outgoingCountSP_DT_PR_CC  = $SubtotalArrayPerSP_DT_PR_CC[$keySP_DT_PR_CC]["Outgoing"]??0;

            $incomingCountSP_DT_PR  = $SubtotalArrayPerSP_DT_PR[$keySP_DT_PR]["Incoming"]??0;
            $outgoingCountSP_DT_PR  = $SubtotalArrayPerSP_DT_PR[$keySP_DT_PR]["Outgoing"]??0;

            $SubtotalArrayPerSP_DT_PR[$keySP_DT_PR] = [
                "SP" => $trans["CN"],
                "DT" => $documentTypeId,
                "PR" => $peppolProcessId,
                "Incoming" =>
                    $incomingCountSP_DT_PR + ($trans["direction"] == "IN")?$trans["count"]:"0",
                "Outgoing" =>
                    $outgoingCountSP_DT_PR + ($trans["direction"] == "OUT")?$trans["count"]:"0"
            ];
            //We can only guess what Receiver Country C4 is (from the Certificate)
            if($trans["direction"] == "IN" && $trans["count"] > 0) {
                $SubtotalArrayPerSP_DT_PR_CC[$keySP_DT_PR_CC] = [
                    "SP" => $trans["CN"],
                    "DT" => $documentTypeId,
                    "PR" => $peppolProcessId,
                    "CCSend" => $trans["C"],//($trans["direction"] == "IN") ? $trans["C"]:"DK",
                    "CCReceive" => "DK",//($trans["direction"] == "OUT") ? $trans["C"]:"DK",
                    "Incoming" =>
                        $incomingCountSP_DT_PR_CC + ($trans["direction"] == "IN")?$trans["count"] : "0",
                    "Outgoing" => 0
//                    $outgoingCountSP_DT_PR_CC + ($trans["direction"] == "OUT")?$trans["count"]:"0"
                ];
            }
        }

        $this->_log->log("Transactions:", Logger::LV_2);
        $this->_log->log($transactions, Logger::LV_2);

        $SubtotalArrayPerSP_DT_PR   = array_values($SubtotalArrayPerSP_DT_PR);
        $SubtotalElements           = [];

        foreach ($SubtotalArrayPerSP_DT_PR_CC as $key => $element)
        {
            $SubtotalElements[$key] = "<Subtotal type=\"PerSP-DT-PR-CC\">";
            $SubtotalElements[$key] .= "    <Key metaSchemeID=\"SP\" schemeID=\"CertSubjectCN\">{$element["SP"]}</Key>";
            $SubtotalElements[$key] .= "    <Key metaSchemeID=\"DT\" schemeID=\"{$element["DT"][0]}\">{$element["DT"][1]}</Key>";
            $SubtotalElements[$key] .= "    <Key metaSchemeID=\"PR\" schemeID=\"{$element["PR"][0]}\">{$element["PR"][1]}</Key>";
            $SubtotalElements[$key] .= "    <Key metaSchemeID=\"CC\" schemeID=\"SenderCountry\">{$element["CCSend"]}</Key>";
            $SubtotalElements[$key] .= "    <Key metaSchemeID=\"CC\" schemeID=\"ReceiverCountry\">{$element["CCReceive"]}</Key>";
            $SubtotalElements[$key] .= "    <Incoming>{$element["Incoming"]}</Incoming>";
            $SubtotalElements[$key] .= "    <Outgoing>{$element["Outgoing"]}</Outgoing>";
            $SubtotalElements[$key] .= "</Subtotal>";
        }

        $key = count($SubtotalElements);
        foreach ($SubtotalArrayPerSP_DT_PR as $element)
        {
            $SubtotalElements[$key] = "<Subtotal type=\"PerSP-DT-PR\">";
            $SubtotalElements[$key] .= "    <Key metaSchemeID=\"SP\" schemeID=\"CertSubjectCN\">{$element["SP"]}</Key>";
            $SubtotalElements[$key] .= "    <Key metaSchemeID=\"DT\" schemeID=\"{$element["DT"][0]}\">{$element["DT"][1]}</Key>";
            $SubtotalElements[$key] .= "    <Key metaSchemeID=\"PR\" schemeID=\"{$element["PR"][0]}\">{$element["PR"][1]}</Key>";
            $SubtotalElements[$key] .= "    <Incoming>{$element["Incoming"]}</Incoming>";
            $SubtotalElements[$key] .= "    <Outgoing>{$element["Outgoing"]}</Outgoing>";
            $SubtotalElements[$key] .= "</Subtotal>";
            $key++;
        }

        return $SubtotalElements;
    }

    /**
     * @throws Exception
     */
    private function generateXml(array $xmlStrings, DateTime $StartDate, DateTime $EndDate, $ReporterID) {

        // Create a new DOMDocument
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false; // Set preserveWhiteSpace to false
        $doc->formatOutput = true; // Set formatOutput to true for proper indentation

        $template   = file_get_contents(__DIR__ . "/TransactionStatisticsReportTemplate.xml");
        $xmlStr     = sprintf(
            $template,
            $StartDate->format("Y-m-d"),
            $EndDate->sub(new DateInterval("P1D"))->format("Y-m-d"),
            $ReporterID);

        // Load the template XML
        $doc->loadXML($xmlStr);

        $totalIncoming = 0;
        $totalOutgoing = 0;

        foreach ($xmlStrings as $xmlString) {
            $subtotal = new DOMDocument();
            $subtotal->preserveWhiteSpace = false;
            $subtotal->formatOutput = true;
            $subtotal->loadXML($xmlString);

            $subtotalType = $subtotal->getElementsByTagName('Subtotal')->item(0);
            $type = ($subtotalType instanceof DOMElement) ? $subtotalType->getAttribute('type') : '';
            if(empty($type))
                $this->_log->log("Subtotal type was not found:\n$xmlString", Logger::LV_1, Logger::LOG_ERR);
            if($type == "PerSP-DT-PR")
            {
                // Get the Incoming and Outgoing values
                $incoming = (int) $subtotal->getElementsByTagName('Incoming')->item(0)->nodeValue;
                $outgoing = (int) $subtotal->getElementsByTagName('Outgoing')->item(0)->nodeValue;

                // Sum up Incoming and Outgoing
                $totalIncoming += $incoming;
                $totalOutgoing += $outgoing;
            }

            // Import the Subtotal node to the main document
            $importedSubtotal = $doc->importNode($subtotal->documentElement, true);
            $doc->documentElement->insertBefore($importedSubtotal, $doc->getElementsByTagName('Total')->item(0)->nextSibling);
        }

        // Get the Total element
        $totalElement = $doc->getElementsByTagName('Total')->item(0);

        // Update the Total Incoming and Outgoing values
        $totalElement->getElementsByTagName('Incoming')->item(0)->nodeValue = $totalIncoming;
        $totalElement->getElementsByTagName('Outgoing')->item(0)->nodeValue = $totalOutgoing;

        // Add additional Subtotal element if Total/Incoming or Total/Outgoing > 0
        if ($totalIncoming > 0 || $totalOutgoing > 0) {
            $newSubtotal = $doc->createElement('Subtotal');
            $newSubtotal->setAttribute('type', 'PerTP');

            $keyElement = $doc->createElement('Key');
            $keyElement->setAttribute('metaSchemeID', 'TP');
            $keyElement->setAttribute('schemeID', 'Peppol');
            $keyElement->nodeValue = 'peppol-transport-as4-v2_0';

            $incomingElement = $doc->createElement('Incoming', $totalIncoming);
            $outgoingElement = $doc->createElement('Outgoing', $totalOutgoing);

            $newSubtotal->appendChild($keyElement);
            $newSubtotal->appendChild($incomingElement);
            $newSubtotal->appendChild($outgoingElement);

            $doc->documentElement->insertBefore($newSubtotal, $totalElement->nextSibling);
        }

        // Return the modified XML as string
        return $doc->saveXML();
    }

    /**
     * @throws Exception
     */
    private function getTRSTransactions(DateTime $startDate, DateTime $endDate): array
    {
        if(!isset($this->_oxalisDB))
            return [];

        $this->_log->log("Start date:\t{$startDate->format("Y-m-01")}");
        $this->_log->log("End date:\t{$endDate->format("Y-m-01")}");

        $selectQuery    = "SELECT \n";
        $selectQuery    .= "    CASE \n";
        $selectQuery    .= "        WHEN mes.status = 'RECEIVED' THEN mes.sender \n";
        $selectQuery    .= "        WHEN mes.status = 'SENT' THEN mes.receiver \n";
        $selectQuery    .= "    END AS endpoint, \n";
        $selectQuery    .= "    mes.document_type_id, \n";
        $selectQuery    .= "    mes.peppol_process_id, \n";
        $selectQuery    .= "    mes.direction, \n";
        $selectQuery    .= "    COUNT(*) as count \n";
        $selectQuery    .= "FROM \n";
        $selectQuery    .= "    Message AS mes \n";
        $selectQuery    .= "WHERE \n";
        $selectQuery    .= "    mes.status IN ('RECEIVED', 'SENT') \n";
        $selectQuery    .= "    AND CONVERT_TZ(mes.created_date, '+00:00', '+02:00') >= '{$startDate->format("Y-m-01 00:00:00")}' \n";
        $selectQuery    .= "    AND CONVERT_TZ(mes.created_date, '+00:00', '+02:00') < '{$endDate->format("Y-m-01 00:00:00")}' \n";
        $selectQuery    .= "GROUP BY \n";
        $selectQuery    .= "    CASE \n";
        $selectQuery    .= "        WHEN mes.status = 'RECEIVED' THEN mes.sender \n";
        $selectQuery    .= "        WHEN mes.status = 'SENT' THEN mes.receiver \n";
        $selectQuery    .= "    END, \n";
        $selectQuery    .= "    mes.document_type_id, \n";
        $selectQuery    .= "    mes.peppol_process_id, \n";
        $selectQuery    .= "    mes.direction \n";
        $selectQuery    .= "ORDER BY \n";
        $selectQuery    .= "     mes.document_type_id, mes.peppol_process_id, endpoint, mes.direction; \n";

        $res            = $this->_oxalisDB->query($selectQuery);
        $transactions   = $res->fetch_all(MYSQLI_ASSOC);

        $this->_log->log("Result:");
        $this->_log->log($transactions);
        foreach ($transactions as $key => $transaction)
        {
            $endpoint = explode("::", $transaction["endpoint"]);
            $htmlCode   = 0;
            $response   = "";
            $this->_lookUpCli->lookupEndpointPeppol(
                end($endpoint),
                $htmlCode,
                $transaction["document_type_id"],
                $response
            );
            $this->_log->log("htmlCode: $htmlCode",
                ((($htmlCode > 201) || ($htmlCode < 200)) ? Logger::LV_2 : Logger::LV_3),
                (($htmlCode > 201) ? Logger::LOG_WARN : (($htmlCode < 200) ? Logger::LOG_ERR : Logger::LOG_OK))
            );
            $x509SubjectName    = $this->getSubjectCommonNameFromCertificate($response, $htmlCode);
            if(empty($x509SubjectName["CN"]))
            {
                $this->_log->log("Endpoint is not registered as PEPPOL: " . end($endpoint), Logger::LV_2, Logger::LOG_WARN);
            }

            $transactions[$key] = array_merge($transaction, $x509SubjectName);
        }

        return array_filter($transactions, fn($innerArray) => !empty($innerArray['CN']));//$transactions;
    }

    /**
     * @throws Exception
     */
    private function getX509SubjectName($xmlString, $htmlCode): array
    {
        if(($htmlCode > 201) || ($htmlCode < 200))
            return ["CN" => "", "C" => ""];
        $xml = new SimpleXMLElement($xmlString);
        $xml->registerXPathNamespace('ds', 'http://www.w3.org/2000/09/xmldsig#');
        $x509SubjectName = $xml->xpath('//ds:X509SubjectName');
        if (!empty($x509SubjectName)) {
            $subjectName = (string)$x509SubjectName[0];
            $pattern = '/CN=([^,]+),.*C=([^,]+)/';
            if (preg_match($pattern, $subjectName, $matches)) {
                $cn = $matches[1];
                $c = $matches[2];
                return ['CN' => $cn, 'C' => $c];
            }
        }
        return ["CN" => "", "C" => ""];
    }

    /**
     * @throws Exception
     */
    private function getSubjectCommonNameFromCertificate($xmlString, $htmlCode): array
    {
        if(($htmlCode > 201) || ($htmlCode < 200))
            return ["CN" => "", "C" => ""];

        $dom = new DOMDocument();
        $dom->loadXML($xmlString);

        $xpath = new DOMXPath($dom);

        $xpath->registerNamespace('ns2', 'http://busdox.org/serviceMetadata/publishing/1.0/');
        $xpath->registerNamespace('ns3', 'http://www.w3.org/2005/08/addressing');

        // Find the Certificate element in both namespaces
        $certificateNodes = $xpath->query('//ns2:Endpoint[@transportProfile="peppol-transport-as4-v2_0"]/ns2:Certificate | //ns3:Endpoint[@transportProfile="peppol-transport-as4-v2_0"]/ns3:Certificate');
        if ($certificateNodes->length > 0)
        {
            $certificate = $certificateNodes->item(0)->textContent;
            $parsedCertificate = openssl_x509_parse("-----BEGIN CERTIFICATE-----\n$certificate\n-----END CERTIFICATE-----\n");
            if ($parsedCertificate && isset($parsedCertificate['subject']['CN']))
            {
                return ["CN" => $parsedCertificate['subject']['CN'], "C" => $parsedCertificate['subject']['C']];
            }
        }
        return ["CN" => "", "C" => ""];
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

    private function post(string $xml, string $args): string
    {
        $this->_errors      = [];
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
        if($httpCode !== 200)
            $this->_errors["HTTP_RESPONSE:$httpCode"] = $response;

        curl_close($curl);
        return $response;
    }

    /**
     * @throws Exception
     */
    private function handleResponse(string $resXml): string
    {
        $responseDocument   = new DOMDocument();
        if(!$responseDocument->loadXML($resXml))
        {
            $this->_errors["NO_XML_RESPONSE"]   = $resXml;
            return "NO_XML_RESPONSE";
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

                $this->_log->log("Error received form Oxalis: $code:'$message'", Logger::LV_1, Logger::LOG_ERR);
            }
        }

        $UUIDNode   = $xpath->query('//api:messageUuid')->item(0);
        return $UUIDNode ? $UUIDNode->textContent : "NO MESSAGE UUID WAS RECEIVED";

    }

    /**
     * @throws Exception
     */
    private function extractCommonNameFromX509Certificate($xmlString)
    {
        $xml            = new SimpleXMLElement($xmlString);
        $securityToken  = $xml->xpath('//*[local-name()="Header"]/*[local-name()="Security"]/*[local-name()="BinarySecurityToken"]');
        if(!isset($securityToken[0]))
            return null;
        $certData       = "-----BEGIN CERTIFICATE----- \n$securityToken[0]\n-----END CERTIFICATE----- \n";
        $this->_log->log("Got Cert:\n$certData");
        $certificate = openssl_x509_read($certData);
        if($certificate)
        {
            $certDetails = openssl_x509_parse($certData);
            return $certDetails['subject']['CN'] ?? null;
        }
        else
        {
            return null;
        }
    }

    /**
     * @throws Exception
     */
    private function extractMessageInfo($xmlString): array
    {
        $xml            = new SimpleXMLElement($xmlString);//Messaging
        $timestamp      = $xml->xpath('//*[local-name()="Header"]/*[local-name()="Messaging"]/*[local-name()="SignalMessage"]/*[local-name()="MessageInfo"]/*[local-name()="Timestamp"]');
        $messageId      = $xml->xpath('//*[local-name()="Header"]/*[local-name()="Messaging"]/*[local-name()="SignalMessage"]/*[local-name()="MessageInfo"]/*[local-name()="MessageId"]');
        $refToMessageId = $xml->xpath('//*[local-name()="Header"]/*[local-name()="Messaging"]/*[local-name()="SignalMessage"]/*[local-name()="MessageInfo"]/*[local-name()="RefToMessageId"]');

        return [
            "Timestamp" => $timestamp[0]??null,
            "MessageId" => $messageId[0]??null,
            "RefToMessageId" => $refToMessageId[0]??null,
        ];
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
            $this->_log->log("Connection to OXALIS DB failed", Logger::LV_1, Logger::LOG_WARN);
            $this->_log->log($this->_oxalisDB->connect_error, Logger::LV_1, Logger::LOG_ERR);
            $this->_oxalisDB = null;
            return false;
        } else {
            $this->_log->log("Connection to OXALIS DB succeeded");
            return true;
        }
    }



}