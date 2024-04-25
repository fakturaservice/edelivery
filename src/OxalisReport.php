<?php

namespace Fakturaservice\Edelivery;

use DateInterval;
use DateTime;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use Fakturaservice\Edelivery\OIOUBL\NetworkType;
use Fakturaservice\Edelivery\util\Logger;
use Fakturaservice\Edelivery\util\LoggerInterface;
use mysqli;

class OxalisReport
{

    private LoggerInterface $_log;
    private string $_url;
    private string $_userName;
    private string $_passWord;
    private ?mysqli $_oxalisDB;
    private string $_className;
    private array $_errors;
    private NemLookUpCli $_lookUpCli;

    /**
     * @throws Exception
     */
    public function __construct(string $url, string $userName, string $passWord, LoggerInterface $logger)
    {
        $this->_url         = $url;
        $this->_userName    = $userName;
        $this->_passWord    = $passWord;
        $this->_className   = basename(str_replace('\\', '/', get_called_class()));
        $this->_log         = $logger;
        $this->_log->setChannel($this->_className);
        $this->_errors      = [];
        $this->_oxalisDB   = null;
        $this->_lookUpCli   = new NemLookUpCli(clone($this->_log));
    }

    public function success(): bool
    {
        return empty($this->_errors);
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

    /**
     * @throws Exception
     */
    public function createTSR(string $startDate, string $reporterCertCN):string
    {
        $this->_log->log("Calling createTSR('$startDate')");
        if(!isset($this->_oxalisDB))
            return "";

        $startDate      = new DateTime($startDate);
        $endDate        = clone ($startDate);
        $endDate->add(new DateInterval("P1M"));

        $transactions                   = $this->getTRSTransactions($startDate, $endDate);
        $sortedTransactions             = $this->sortingTSRTransactions($transactions);
        return $this->generateTSRXml($sortedTransactions, $startDate, $endDate, $reporterCertCN);

    }

    /**
     * @throws Exception
     */
    public function createEUSR(string $startDate, string $reporterCertCN): string
    {
        $this->_log->log("Calling createEUSR('$startDate')");
        if(!isset($this->_oxalisDB))
            return "";

        $startDate  = new DateTime($startDate);
        $endDate    = clone ($startDate);
        $endDate->add(new DateInterval("P1M"));

        $endUsers   = $this->getEUSTEndUsers($startDate, $endDate);
        $this->_log->log("END USER XML ARRAY:", Logger::LV_3);
        $this->_log->log($endUsers, Logger::LV_3);

        $xml        = $this->generateEUSTXml($endUsers, $startDate, $endDate, $reporterCertCN);

        $this->_log->log("END USER XML RESULT:\n$xml", Logger::LV_2);

        return $xml;
    }

    private function sortingTSRTransactions($transactions): array
    {
        $SubtotalArrayPerSP_DT_PR       = [];
        $SubtotalArrayPerSP_DT_PR_CC    = [];
        foreach ($transactions as $trans)
        {
            $documentTypeId     = explode("::", $trans["document_type_id"], 2);
            $peppolProcessId    = explode("::", $trans["peppol_process_id"], 2);

            if(($documentTypeId[0] !== NemLookUpCli::BUSINESS_SCOPE_DOC_ID_IDENTIFIER_BUSDOX) || empty($trans["CN"]))
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
    private function generateTSRXml(array $xmlStrings, DateTime $StartDate, DateTime $EndDate, $reporterCertCN)
    {
        // Create a new DOMDocument
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false; // Set preserveWhiteSpace to false
        $doc->formatOutput = true; // Set formatOutput to true for proper indentation

        $template   = file_get_contents(__DIR__ . "/resources/TransactionStatisticsReportTemplate.xml");
        $xmlStr     = sprintf(
            $template,
            $StartDate->format("Y-m-d"),
            $EndDate->sub(new DateInterval("P1D"))->format("Y-m-d"),
            $reporterCertCN);

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
    private function generateEUSTXml(array $endUsers, DateTime $startDate, DateTime $endDate, string $reporterCertCN) {

        $template = file_get_contents(__DIR__ . "/resources/EndUserStatisticsReportTemplate.xml");
        $insertionPoint = '</Header>';

        $template = sprintf(
            $template,
            $startDate->format("Y-m-d"),
            $endDate->sub(new DateInterval("P1D"))->format("Y-m-d"),
            $reporterCertCN
        );

        $endUsers = implode("", $endUsers);
        return str_replace($insertionPoint, $insertionPoint . $endUsers, $template);
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
    private function getEUSTEndUsers(DateTime $startDate, DateTime $endDate): array
    {
        if(!isset($this->_oxalisDB))
            return [];

        $this->_log->log("Start date:\t{$startDate->format("Y-m-01")}");
        $this->_log->log("End date:\t{$endDate->format("Y-m-01")}");

        $selectQuery    = "SELECT \n";
        $selectQuery    .= " mes.sender, \n";
        $selectQuery    .= " mes.receiver, \n";
        $selectQuery    .= " mes.document_type_id as DT, \n";
        $selectQuery    .= " mes.peppol_process_id as PR, \n";
        $selectQuery    .= " mes.created_date, \n";
        $selectQuery    .= " SUBSTRING_INDEX( \n";
        $selectQuery    .= "    mesCont.data, \n";
        $selectQuery    .= "    '</StandardBusinessDocumentHeader>', \n";
        $selectQuery    .= "    1 \n";
        $selectQuery    .= " ) AS CC \n";
        $selectQuery    .= "FROM \n";
        $selectQuery    .= " Message AS mes LEFT JOIN Message_Content AS mesCont ON mes.message_content_id = mesCont.id \n";
        $selectQuery    .= "WHERE \n";
        $selectQuery    .= " mes.status IN ('RECEIVED', 'SENT') \n";
        $selectQuery    .= " AND mesCont.data LIKE '%<Type>COUNTRY_C1</Type>%' \n";
        $selectQuery    .= " AND mes.peppol_process_id != 'urn:fdc:peppol.eu:poacc:bis:invoice_response:3' \n";
        $selectQuery    .= " AND CONVERT_TZ(mes.created_date, '+00:00', '+02:00') >= '{$startDate->format("Y-m-01 00:00:00")}' \n";
        $selectQuery    .= " AND CONVERT_TZ(mes.created_date, '+00:00', '+02:00') < '{$endDate->format("Y-m-01 00:00:00")}'; \n";

        $res            = $this->_oxalisDB->query($selectQuery);
        $endUsers       = $res->fetch_all(MYSQLI_ASSOC);

        $this->_log->log("Result:", Logger::LV_2);
        $this->_log->log($endUsers, Logger::LV_2);

        $uniqueSending = $uniqueReceiving = $uniqueSendingOrReceiving = 0;
        $uniqueSenders = $uniqueReceivers = $uniqueSendersOrReceivers = [];

        foreach ($endUsers as $key => $endUser)
        {
            $endUsers[$key]["CC"] = $this->getC1FromContent($endUser["CC"]);
            if(!in_array($endUser['sender'], $uniqueSenders))
            {
                $uniqueSenders[] = $endUser['sender'];
                $uniqueSending++;
            }
            if(!in_array($endUser['receiver'], $uniqueReceivers))
            {
                $uniqueReceivers[] = $endUser['receiver'];
                $uniqueReceiving++;
            }
            if(!in_array($endUser['sender'], $uniqueSendersOrReceivers))
            {
                $uniqueSendersOrReceivers[] = $endUser['sender'];
                $uniqueSendingOrReceiving++;
            }
            if(!in_array($endUser['receiver'], $uniqueSendersOrReceivers))
            {
                $uniqueSendersOrReceivers[] = $endUser['receiver'];
                $uniqueSendingOrReceiving++;
            }
        }

        $EUSTArray = [
            "FullSet" => [
                "SendingEndUsers" => $uniqueSending,
                "ReceivingEndUsers" => $uniqueReceiving,
                "SendingOrReceivingEndUsers" => $uniqueSendingOrReceiving,
            ],
            "PerEUC" => $this->getPerEUC($endUsers),
            "PerDT-EUC" => $this->getPerDT_EUC($endUsers),
            "PerDT-PR-EUC" => $this->getPerDT_PR_EUC($endUsers),
            "PerDT-PR" => $this->getPerDT_PR($endUsers)
        ];

        $EUSTXmlSets    = [];
        $elementCount   = 0;
        foreach ($EUSTArray as $key => $subsets)
        {
            if($key == "FullSet")
            {
                $EUSTXmlSets[$elementCount] = "\n<FullSet>\n";
                $EUSTXmlSets[$elementCount] .= "    <SendingEndUsers>{$subsets["SendingEndUsers"]}</SendingEndUsers>\n";
                $EUSTXmlSets[$elementCount] .= "    <ReceivingEndUsers>{$subsets["ReceivingEndUsers"]}</ReceivingEndUsers>\n";
                $EUSTXmlSets[$elementCount] .= "    <SendingOrReceivingEndUsers>{$subsets["SendingOrReceivingEndUsers"]}</SendingOrReceivingEndUsers>\n";
                $EUSTXmlSets[$elementCount] .= "</FullSet>\n";
                $elementCount++;
            }
            else
            {
                foreach ($subsets as $subset)
                {
                    $EUSTXmlSets[$elementCount] = "\n<Subset type=\"$key\">\n";
                    if(isset($subset["DT"]))
                    {
                        $DT = explode("::", $subset["DT"], 2);
                        $EUSTXmlSets[$elementCount] .= "    <Key metaSchemeID=\"DT\" schemeID=\"{$DT[0]}\">{$DT[1]}</Key>\n";
                    }
                    if(isset($subset["PR"]))
                    {
                        $PR = explode("::", $subset["PR"], 2);
                        $EUSTXmlSets[$elementCount] .= "    <Key metaSchemeID=\"PR\" schemeID=\"{$PR[0]}\">{$PR[1]}</Key>\n";
                    }
                    if(isset($subset["CC"]))
                        $EUSTXmlSets[$elementCount] .= "    <Key metaSchemeID=\"CC\" schemeID=\"EndUserCountry\">{$subset["CC"]}</Key>\n";

                    if(isset($subset["SendingEndUsers"]))
                        $EUSTXmlSets[$elementCount] .= "    <SendingEndUsers>{$subset["SendingEndUsers"]}</SendingEndUsers>\n";

                    if(isset($subset["ReceivingEndUsers"]))
                        $EUSTXmlSets[$elementCount] .= "    <ReceivingEndUsers>{$subset["ReceivingEndUsers"]}</ReceivingEndUsers>\n";

                    if(isset($subset["SendingOrReceivingEndUsers"]))
                        $EUSTXmlSets[$elementCount] .= "    <SendingOrReceivingEndUsers>{$subset["SendingOrReceivingEndUsers"]}</SendingOrReceivingEndUsers>\n";

                    $EUSTXmlSets[$elementCount] .= "</Subset>\n";
                    $elementCount++;
                }
            }
        }
        return $EUSTXmlSets;

    }

    private function getPerEUC($endUsers): array
    {
        $PerEUC = [];
        foreach($endUsers as $index => $entry)
        {
            $uniqueSending = $uniqueReceiving = $uniqueSendingOrReceiving = 0;
            $uniqueSenders = $uniqueReceivers = $uniqueSendersOrReceivers = [];

            foreach($endUsers as $entry2)
            {
                if($entry['CC'] == $entry2['CC'])
                {
                    if(!in_array($entry2['sender'], $uniqueSenders))
                    {
                        $uniqueSenders[] = $entry2['sender'];
                        $uniqueSending++;
                    }
                    if(!in_array($entry2['receiver'], $uniqueReceivers))
                    {
                        $uniqueReceivers[] = $entry2['receiver'];
                        $uniqueReceiving++;
                    }
                    if(!in_array($entry2['sender'], $uniqueSendersOrReceivers))
                    {
                        $uniqueSendersOrReceivers[] = $entry2['sender'];
                        $uniqueSendingOrReceiving++;
                    }
                    if(!in_array($entry2['receiver'], $uniqueSendersOrReceivers))
                    {
                        $uniqueSendersOrReceivers[] = $entry2['receiver'];
                        $uniqueSendingOrReceiving++;
                    }
                }
            }
            $exists = false;
            foreach($PerEUC as $key => $data)
            {
                if($data['CC'] == $entry['CC'])
                {
                    $exists = true;
                    $PerEUC[$key]['SendingEndUsers'] = $uniqueSending;
                    $PerEUC[$key]['ReceivingEndUsers'] = $uniqueReceiving;
                    $PerEUC[$key]['SendingOrReceivingEndUsers'] = $uniqueSendingOrReceiving;
                    break;
                }
            }
            if(!$exists)
            {
                $PerEUC[] = [
                    "CC" => $entry['CC'],
                    "SendingEndUsers" => $uniqueSending,
                    "ReceivingEndUsers" => $uniqueReceiving,
                    "SendingOrReceivingEndUsers" => $uniqueSendingOrReceiving
                ];
            }
        }
        return $PerEUC;
    }
    private function getPerDT_EUC($endUsers): array
    {
        $PerDT_EUC = [];
        foreach($endUsers as $index => $entry)
        {
            $uniqueSending = $uniqueReceiving = $uniqueSendingOrReceiving = 0;
            $uniqueSenders = $uniqueReceivers = $uniqueSendersOrReceivers = [];

            foreach($endUsers as $entry2)
            {
                if($entry['DT'] == $entry2['DT'] && $entry['CC'] == $entry2['CC'])
                {
                    if(!in_array($entry2['sender'], $uniqueSenders))
                    {
                        $uniqueSenders[] = $entry2['sender'];
                        $uniqueSending++;
                    }
                    if(!in_array($entry2['receiver'], $uniqueReceivers))
                    {
                        $uniqueReceivers[] = $entry2['receiver'];
                        $uniqueReceiving++;
                    }
                    if(!in_array($entry2['sender'], $uniqueSendersOrReceivers))
                    {
                        $uniqueSendersOrReceivers[] = $entry2['sender'];
                        $uniqueSendingOrReceiving++;
                    }
                    if(!in_array($entry2['receiver'], $uniqueSendersOrReceivers))
                    {
                        $uniqueSendersOrReceivers[] = $entry2['receiver'];
                        $uniqueSendingOrReceiving++;
                    }
                }
            }
            $exists = false;
            foreach($PerDT_EUC as $key => $data)
            {
                if($data['DT'] == $entry['DT'] && $data['CC'] == $entry['CC'])
                {
                    $exists = true;
                    $PerDT_EUC[$key]['SendingEndUsers'] = $uniqueSending;
                    $PerDT_EUC[$key]['ReceivingEndUsers'] = $uniqueReceiving;
                    $PerDT_EUC[$key]['SendingOrReceivingEndUsers'] = $uniqueSendingOrReceiving;
                    break;
                }
            }
            if(!$exists)
            {
                $PerDT_EUC[] = [
                    "DT" => $entry['DT'],
                    "CC" => $entry['CC'],
                    "SendingEndUsers" => $uniqueSending,
                    "ReceivingEndUsers" => $uniqueReceiving,
                    "SendingOrReceivingEndUsers" => $uniqueSendingOrReceiving
                ];
            }
        }
        return $PerDT_EUC;
    }
    private function getPerDT_PR_EUC($endUsers): array
    {
        $PerDT_PR_EUC = [];
        foreach($endUsers as $index => $entry)
        {
            $uniqueSending = $uniqueReceiving = $uniqueSendingOrReceiving = 0;
            $uniqueSenders = $uniqueReceivers = $uniqueSendersOrReceivers = [];

            foreach($endUsers as $entry2)
            {
                if($entry['DT'] == $entry2['DT'] && $entry['PR'] == $entry2['PR'] && $entry['CC'] == $entry2['CC'])
                {
                    if(!in_array($entry2['sender'], $uniqueSenders))
                    {
                        $uniqueSenders[] = $entry2['sender'];
                        $uniqueSending++;
                    }
                    if(!in_array($entry2['receiver'], $uniqueReceivers))
                    {
                        $uniqueReceivers[] = $entry2['receiver'];
                        $uniqueReceiving++;
                    }
                    if(!in_array($entry2['sender'], $uniqueSendersOrReceivers))
                    {
                        $uniqueSendersOrReceivers[] = $entry2['sender'];
                        $uniqueSendingOrReceiving++;
                    }
                    if(!in_array($entry2['receiver'], $uniqueSendersOrReceivers))
                    {
                        $uniqueSendersOrReceivers[] = $entry2['receiver'];
                        $uniqueSendingOrReceiving++;
                    }
                }
            }
            $exists = false;
            foreach($PerDT_PR_EUC as $key => $data)
            {
                if($data['DT'] == $entry['DT'] && $data['PR'] == $entry['PR'] && $data['CC'] == $entry['CC'])
                {
                    $exists = true;
                    $PerDT_PR_EUC[$key]['SendingEndUsers'] = $uniqueSending;
                    $PerDT_PR_EUC[$key]['ReceivingEndUsers'] = $uniqueReceiving;
                    $PerDT_PR_EUC[$key]['SendingOrReceivingEndUsers'] = $uniqueSendingOrReceiving;
                    break;
                }
            }
            if(!$exists)
            {
                $PerDT_PR_EUC[] = [
                    "DT" => $entry['DT'],
                    "PR" => $entry['PR'],
                    "CC" => $entry['CC'],
                    "SendingEndUsers" => $uniqueSending,
                    "ReceivingEndUsers" => $uniqueReceiving,
                    "SendingOrReceivingEndUsers" => $uniqueSendingOrReceiving
                ];
            }
        }
        return $PerDT_PR_EUC;
    }
    private function getPerDT_PR($endUsers): array
    {
        $PerDT_PR = [];
        foreach($endUsers as $index => $entry)
        {
            $uniqueSending = $uniqueReceiving = $uniqueSendingOrReceiving = 0;
            $uniqueSenders = $uniqueReceivers = $uniqueSendersOrReceivers = [];

            foreach($endUsers as $entry2)
            {
                if($entry['DT'] == $entry2['DT'] && $entry['PR'] == $entry2['PR'])
                {
                    if(!in_array($entry2['sender'], $uniqueSenders))
                    {
                        $uniqueSenders[] = $entry2['sender'];
                        $uniqueSending++;
                    }
                    if(!in_array($entry2['receiver'], $uniqueReceivers))
                    {
                        $uniqueReceivers[] = $entry2['receiver'];
                        $uniqueReceiving++;
                    }
                    if(!in_array($entry2['sender'], $uniqueSendersOrReceivers))
                    {
                        $uniqueSendersOrReceivers[] = $entry2['sender'];
                        $uniqueSendingOrReceiving++;
                    }
                    if(!in_array($entry2['receiver'], $uniqueSendersOrReceivers))
                    {
                        $uniqueSendersOrReceivers[] = $entry2['receiver'];
                        $uniqueSendingOrReceiving++;
                    }
                }
            }
            $exists = false;
            foreach($PerDT_PR as $key => $data)
            {
                if($data['DT'] == $entry['DT'] && $data['PR'] == $entry['PR'])
                {
                    $exists = true;
                    $PerDT_PR[$key]['SendingEndUsers'] = $uniqueSending;
                    $PerDT_PR[$key]['ReceivingEndUsers'] = $uniqueReceiving;
                    $PerDT_PR[$key]['SendingOrReceivingEndUsers'] = $uniqueSendingOrReceiving;
                    break;
                }
            }
            if(!$exists)
            {
                $PerDT_PR[] = [
                    "DT" => $entry['DT'],
                    "PR" => $entry['PR'],
                    "SendingEndUsers" => $uniqueSending,
                    "ReceivingEndUsers" => $uniqueReceiving,
                    "SendingOrReceivingEndUsers" => $uniqueSendingOrReceiving
                ];
            }
        }
        return $PerDT_PR;
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

    private function getC1FromContent($data): string
    {
        preg_match('/<Scope><Type>COUNTRY_C1<\/Type><InstanceIdentifier>(.*?)<\/InstanceIdentifier>/', $data, $matches);
        preg_match_all('/<Scope>\s*<Type>COUNTRY_C1<\/Type>\s*<InstanceIdentifier>(.*?)<\/InstanceIdentifier>\s*<\/Scope>/', $data, $matches);

        return empty($matches[1][0])?"ZZ":$matches[1][0];
    }

}