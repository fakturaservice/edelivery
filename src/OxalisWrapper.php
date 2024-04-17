<?php


namespace Fakturaservice\Edelivery;


use DateTime;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use ReflectionClass;
use Fakturaservice\Edelivery\{
    util\LoggerInterface,
    util\Logger,
    OIOUBL\ICD,
    OIOUBL\CatalogueType,
    OIOUBL\CustomizationID,
    OIOUBL\EAS,
    OIOUBL\NetworkType,
    OIOUBL\ProfileID
};

class OxalisWrapper
{
    const DEFAULT_TSR_VERSION_ID            = "1.0";
    const DEFAULT_EUSR_VERSION_ID           = "1.1";
    const PEPPOL_REPORTING_ENDPOINT_TYPE    = "9925";
    const PEPPOL_REPORTING_ENDPOINT         = "be0848934496";
    const DEFAULT_UBL_VERSION_ID            = "2.1";
    const TRANSACTION_PARTICIPANT_SENDER    = "Sender";
    const TRANSACTION_PARTICIPANT_RECEIVER  = "Receiver";



    private Logger $_log;

    private array $_docTypesNsPrefixes = [
        CatalogueType::Invoice              => "xmlns:inv",
        CatalogueType::CreditNote           => "xmlns:cre",
        CatalogueType::Reminder             => "xmlns:rem",
        CatalogueType::ApplicationResponse  => "xmlns:app"
    ];

    private string $_sbdTemplatePath = __DIR__ . "/SBDTemplate.xml";
    private DOMDocument $_sbdTemplateDocument;
    private DOMDocument $_payloadDocument;
    private string $_type;
    private string $_className;


    /**
     * @throws Exception
     */
    public function __construct(string $payloadXmlString, LoggerInterface $logger)
    {
        $this->_className       = basename(str_replace('\\', '/', get_called_class()));
        $this->_log             = $logger;
        $this->_log->setChannel($this->_className);

        $this->_payloadDocument = new DOMDocument();
        $this->_payloadDocument->loadXML($payloadXmlString);
        $this->_type            = $this->getDocumentType();

        $this->_sbdTemplateDocument = new DOMDocument();
        $this->_sbdTemplateDocument->loadXML(file_get_contents($this->_sbdTemplatePath));
    }
    /**
     * @throws Exception
     */
    public function wrapSBD($glnCh=NetworkType::NemHandel_AS4, $forceSender=null, $useDocTypeNSPrefix=false, $usePayloadVersions=true): string
    {
        $this->_log->log((
        $usePayloadVersions?
            "Using versions from payload document":
            "Using hardcoded default versions"),
            Logger::LV_2, ($usePayloadVersions?Logger::LOG_OK:Logger::LOG_WARN));

        $today                          = new DateTime();
        $documentIdentificationStandard = $this->getDocumentIdentificationStandard();
        $UBLVersionID                   = $this->getUBLVersionID($usePayloadVersions);
        $customizationID                = $this->getCustomizationID($glnCh, $usePayloadVersions);
        $profileID                      = $this->getProfileID($glnCh, $usePayloadVersions);
        $endpoints                      = $this->getEndpoints();
        $countryC1                      = $this->getCountryC1();

        $documentIdInstanceIdentifier   = "$documentIdentificationStandard::$this->_type##$customizationID::$UBLVersionID";

        if($useDocTypeNSPrefix)
            $this->setDocTypeNS($documentIdentificationStandard);

        $this->setSbdDocumentIdentification(
            $documentIdentificationStandard,//$documentIdInstanceIdentifier
            $UBLVersionID,
            $this->generateUUID(),
            $today);

        $this->setSbdBusinessScope(
            $glnCh,
            $documentIdInstanceIdentifier,
            $profileID,
            $countryC1
        );

        $this->setSbdSenderAndReceiver(
            $forceSender??$endpoints[self::TRANSACTION_PARTICIPANT_SENDER],
            $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]);

        $modifiedXml    = $this->setSbdPayload();

        $this->_log->log("Final wrapped document:\n\n$modifiedXml");

        return $modifiedXml;
    }
    /**
     * @throws Exception
     */
    public function getEndpoints($transactionParticipant=null)
    {
        $endpoints = [];
        $xpath = new DOMXPath($this->_payloadDocument);
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

        switch($this->_type)
        {
            case CatalogueType::Invoice:
            case CatalogueType::CreditNote:
            case CatalogueType::Reminder:
            {
                // Extract values from AccountingSupplierParty
                $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpoint"]        =
                    $xpath->query('//cac:AccountingSupplierParty/cac:Party/cbc:EndpointID')->item(0)->textContent;
                $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpointType"]    =
                    $xpath->query('//cac:AccountingSupplierParty/cac:Party/cbc:EndpointID/@schemeID')->item(0)->textContent;

                // Extract values from AccountingCustomerParty
                $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpoint"]      =
                    $xpath->query('//cac:AccountingCustomerParty/cac:Party/cbc:EndpointID')->item(0)->textContent;
                $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpointType"]  =
                    $xpath->query('//cac:AccountingCustomerParty/cac:Party/cbc:EndpointID/@schemeID')->item(0)->textContent;
                break;
            }
            case CatalogueType::ApplicationResponse:
            {
                // Extract values from AccountingSupplierParty
                $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpoint"]        =
                    $xpath->query('//cac:SenderParty/cbc:EndpointID')->item(0)->textContent;
                $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpointType"]    =
                    $xpath->query('//cac:SenderParty/cbc:EndpointID/@schemeID')->item(0)->textContent;

                // Extract values from AccountingCustomerParty
                $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpoint"]      =
                    $xpath->query('//cac:ReceiverParty/cbc:EndpointID')->item(0)->textContent;
                $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpointType"]  =
                    $xpath->query('//cac:ReceiverParty/cbc:EndpointID/@schemeID')->item(0)->textContent;
                break;
            }
            case CatalogueType::TransactionStatisticsReport:
            case CatalogueType::EndUserStatisticsReport:
            {
                //Need to use forceSender in function wrapSBD()
                $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpoint"]        = "";
                $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpointType"]    = "";

                //Access point name: Official OpenPeppol Reporting AP
                $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpoint"]      = self::PEPPOL_REPORTING_ENDPOINT;
                $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpointType"]  = self::PEPPOL_REPORTING_ENDPOINT_TYPE;
                break;
            }
        }

        /** TODO: Remove this hack!
         * ******************************************* HACK START *******************************************
         * This hack is for detecting if the endpoint is holding the ICD value instead of using @schemeID
         * Ex: <cbc:EndpointID schemeID="ZZZ">0192:745707327</cbc:EndpointID>
         * **************************************************************************************************
         */

        $senderHacked   = false;
        $receiverHacked = false;
        $icdValues      = array_values((new ReflectionClass(ICD::class))->getConstants());

        $senderEndpointIDHackArray      = explode(":", $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpoint"]);
        $receiverEndpointIDHackArray    = explode(":", $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpoint"]);

        if((count($senderEndpointIDHackArray) > 1) && (in_array($senderEndpointIDHackArray[0], $icdValues)))
        {
            $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpointType"]    =
                $senderEndpointIDHackArray[0];

            $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpoint"]        =
                preg_replace('/\d+:/', '', $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpoint"]);

            $senderHacked = true;
        }
        if((count($receiverEndpointIDHackArray) > 1) && (in_array($receiverEndpointIDHackArray[0], $icdValues)))
        {
            $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpointType"]    =
                $receiverEndpointIDHackArray[0];

            $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpoint"]        =
                preg_replace('/\d+:/', '', $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpoint"]);

            $receiverHacked = true;
        }
        /** ******************************************* HACK END ******************************************* */


        //TODO: Remove this hack!
        if(!$senderHacked) {
            $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpoint"]        = trim($endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpoint"]);
            $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpointType"]    = //If @schemeID is NULL then '0088'
                EAS::getId(($endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpointType"] ?? ICD::GLN));
        }

        //TODO: Remove this hack!
        if(!$receiverHacked) {
            $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpoint"]      = trim($endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpoint"]);
            $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpointType"]  = //If @schemeID is NULL then '0088'
                EAS::getId(($endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpointType"] ?? ICD::GLN));
        }

        $endpoints[self::TRANSACTION_PARTICIPANT_SENDER]    =
            "{$endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpointType"]}:{$endpoints[self::TRANSACTION_PARTICIPANT_SENDER]["endpoint"]}";

        $endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]  =
            "{$endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpointType"]}:{$endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]["endpoint"]}";

        $this->_log->log("Found sender:             {$endpoints[self::TRANSACTION_PARTICIPANT_SENDER]}", Logger::LV_2);
        $this->_log->log("Found receiver:           {$endpoints[self::TRANSACTION_PARTICIPANT_RECEIVER]}", Logger::LV_2);


        return $transactionParticipant?$endpoints[$transactionParticipant]:$endpoints;
    }
    public function getErrorMsg() : string
    {
        return $this->_log->getErrorMsg();
    }

    /**
     * @throws Exception
     */
    private function getDocumentType(): string
    {
        $xpath = new DOMXPath($this->_payloadDocument);
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

        // Use XPath to find the first element node (ignoring text nodes)
        $firstElement = $xpath->query("//*[not(self::text())][1]")->item(0);

        // Get the name of the found element
        if ($firstElement === null)
        {
            $this->_log->log("No root element found!!!", Logger::LV_1, Logger::LOG_ERR);
        }
        $firstElementTagName = preg_replace('/^\w+:/', '', $firstElement->nodeName);
        if(!in_array($firstElementTagName, [
            CatalogueType::Invoice,
            CatalogueType::CreditNote,
            CatalogueType::Reminder,
            CatalogueType::ApplicationResponse,
            CatalogueType::TransactionStatisticsReport,
            CatalogueType::EndUserStatisticsReport
        ]))
            $this->_log->log("'$firstElementTagName' Is not a valid document type", Logger::LV_1, Logger::LOG_ERR);

        $this->_log->log("'$firstElementTagName' was found to be a valid document type");
        return $firstElementTagName;
    }
    /**
     * @throws Exception
     */
    private function getUBLVersionID(bool $usePayload=false): string
    {
        $xpath = new DOMXPath($this->_payloadDocument);
        if(in_array($this->_type, [CatalogueType::TransactionStatisticsReport, CatalogueType::EndUserStatisticsReport]))
        {
            $xmlns          = $this->getDocumentIdentificationStandard();
            $parts          = explode(':', $xmlns);
            $UBLVersionID   = end($parts);
        }
        else
        {
            $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

            // Extract values from AccountingSupplierParty
            $UBLVersionIDItm    = $xpath->query('//cbc:UBLVersionID')->item(0);
            $UBLVersionID   = ($UBLVersionIDItm !== null)?$UBLVersionIDItm->textContent:self::DEFAULT_UBL_VERSION_ID;

            if(!$usePayload)
            {
                $this->_log->log("Found UBLVersionID:       $UBLVersionID (Falling back to default: '" .
                    self::DEFAULT_UBL_VERSION_ID . "')", Logger::LV_2, Logger::LOG_WARN);
                return self::DEFAULT_UBL_VERSION_ID;
            }
            $this->_log->log("Found UBLVersionID:       $UBLVersionID", Logger::LV_2);
        }


        return $UBLVersionID;
    }

    /**
     * @throws Exception
     */
    private function getCustomizationID($glnCh, bool $usePayload=false): string
    {
        $xpath = new DOMXPath($this->_payloadDocument);
        if(in_array($this->_type, [CatalogueType::TransactionStatisticsReport, CatalogueType::EndUserStatisticsReport]))
        {
            $xmlns = $this->_payloadDocument->lookupNamespaceURI(null);
            $xpath->registerNamespace('ns', $xmlns);

            // Extract values from AccountingSupplierParty
            $customizationIDItm = $xpath->query('//ns:CustomizationID')->item(0);
            $customizationID    = ($customizationIDItm !== null)?
                $customizationIDItm->textContent:
                (($this->_type == CatalogueType::TransactionStatisticsReport)?self::DEFAULT_TSR_VERSION_ID:self::DEFAULT_EUSR_VERSION_ID);
        }
        else
        {
            $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

            // Extract values from AccountingSupplierParty
            $customizationIDItm = $xpath->query('//cbc:CustomizationID')->item(0);
            $customizationID    = ($customizationIDItm !== null)?$customizationIDItm->textContent:self::DEFAULT_UBL_VERSION_ID;

            if(!$usePayload)
            {
                if($glnCh == NetworkType::PEPPOL_AS4)
                {
                    $this->_log->log("Found CustomizationID:    $customizationID (Falling back to default: '" .
                        CustomizationID::peppol_poacc_trns_mlr_3 . "')", Logger::LV_2, Logger::LOG_WARN);
                    return CustomizationID::peppol_poacc_trns_mlr_3;
                }
                $this->_log->log("Found CustomizationID:    $customizationID (Falling back to default: '" .
                    CustomizationID::oioubl_2_1 . "')", Logger::LV_2, Logger::LOG_WARN);
                return CustomizationID::oioubl_2_1;
            }
        }

        $this->_log->log("Found CustomizationID:    $customizationID", Logger::LV_2);
        return $customizationID;
    }

    /**
     * @throws Exception
     */
    private function getProfileID($glnCh, bool $usePayload=false): string
    {
        $xpath = new DOMXPath($this->_payloadDocument);
        if(in_array($this->_type, [CatalogueType::TransactionStatisticsReport, CatalogueType::EndUserStatisticsReport]))
        {
            $xmlns = $this->_payloadDocument->lookupNamespaceURI(null);
            $xpath->registerNamespace('ns', $xmlns);

            // Extract values from AccountingSupplierParty
            $profileIDItm   = $xpath->query('//ns:ProfileID')->item(0);
            $profileID      = ($profileIDItm !== null)?$profileIDItm->textContent:ProfileID::peppol_eu_edec_bis_reporting_1;
        }
        else
        {
            $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

            // Extract values from AccountingSupplierParty
            $profileIDItm   = $xpath->query('//cbc:ProfileID')->item(0);
            $profileID      = ($profileIDItm !== null)?$profileIDItm->textContent:ProfileID::procurement_BilSim_1_0;

            if(!$usePayload)
            {
                if($glnCh == NetworkType::PEPPOL_AS4)
                {
                    $this->_log->log("Found profileID:          $profileID (Falling back to default: '" .
                        ProfileID::peppol_poacc_bis_mlr_3 . "')", Logger::LV_2, Logger::LOG_WARN);
                    return ProfileID::peppol_poacc_bis_mlr_3;
                }
                $this->_log->log("Found profileID:          $profileID (Falling back to default: '" .
                    ProfileID::procurement_BilSim_1_0 . "')", Logger::LV_2, Logger::LOG_WARN);
                return ProfileID::procurement_BilSim_1_0;
            }



        }
        $this->_log->log("Found profileID:          $profileID", Logger::LV_2);
        return $profileID;
    }

    /**
     * @throws ORException
     * @throws Exception
     */
    private function getCountryC1(): string
    {
        $countryC1  = "DK";
        $xpath = new DOMXPath($this->_payloadDocument);
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

        switch($this->_type)
        {
            case CatalogueType::Invoice:
            case CatalogueType::CreditNote:
            case CatalogueType::Reminder:
            {
                // Extract values from AccountingSupplierParty
                $IdentificationCode = $xpath->query('//cac:AccountingSupplierParty/cac:Party/cac:PostalAddress/cac:Country/cbc:IdentificationCode')->item(0);
                $countryC1          = $IdentificationCode->textContent??$countryC1;
                break;
            }
            case CatalogueType::ApplicationResponse:
            {
                // Extract values from AccountingSupplierParty
                $IdentificationCode = $xpath->query('//cac:SenderParty/cac:PostalAddress/cac:Country/cbc:IdentificationCode')->item(0);
                $countryC1          = $IdentificationCode->textContent??$countryC1;
                break;
            }
        }


        $this->_log->log("Found CountryC1:          $countryC1", Logger::LV_2);

        return $countryC1;
    }

    private function getDocumentIdentificationStandard(): string
    {
        // Get the namespace URI (URN) for the "xmlns" namespace declaration
        return $this->_payloadDocument->lookupNamespaceURI(null);
    }

    /**
     * @throws Exception
     */
    private function getUUID(): string
    {
        $xpath = new DOMXPath($this->_payloadDocument);
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

        // Extract values from AccountingSupplierParty
        $UUID   = $xpath->query('//cbc:UUID')->item(0)->textContent;

        $this->_log->log("Found UUID:               $UUID", Logger::LV_2);

        return $UUID;
    }

    private function setDocTypeNS(string $standard)
    {
        // Find the root element and replace the namespace definition
        $root = $this->_sbdTemplateDocument->documentElement;
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', $this->_docTypesNsPrefixes[$this->_type], $standard);
    }
    private function setSbdDocumentIdentification(
        string    $standard,
        string    $typeVersion,
        string    $instanceIdentifier,
        DateTime $creationDateAndTime)
    {

        $xpath = new DOMXPath($this->_sbdTemplateDocument);
        $xpath->registerNamespace('ns', 'http://www.unece.org/cefact/namespaces/StandardBusinessDocumentHeader');

        // Find the DocumentIdentification element
        $documentIdentificationNode = $xpath->query('//ns:DocumentIdentification')->item(0);

        if ($documentIdentificationNode instanceof DOMElement)
        {
            $documentIdentification = $documentIdentificationNode;

            // Update child elements with new values
            $documentIdentification->getElementsByTagName('Standard')
                ->item(0)->nodeValue = $standard;
            $documentIdentification->getElementsByTagName('TypeVersion')
                ->item(0)->nodeValue = $typeVersion;
            $documentIdentification->getElementsByTagName('InstanceIdentifier')
                ->item(0)->nodeValue = $instanceIdentifier;
            $documentIdentification->getElementsByTagName('Type')
                ->item(0)->nodeValue = $this->_type;
            $documentIdentification->getElementsByTagName('CreationDateAndTime')
                ->item(0)->nodeValue = $creationDateAndTime->format("Y-m-d\TH:i:s.v");
        }
    }

    /**
     * @throws Exception
     */
    private function setSbdBusinessScope(
        int       $glnCh,
        string    $documentIdInstanceIdentifier,
        string    $processIdInstanceIdentifier,
        string    $countryC1InstanceIdentifier)
    {
        $xpath = new DOMXPath($this->_sbdTemplateDocument);
        $xpath->registerNamespace('ns', 'http://www.unece.org/cefact/namespaces/StandardBusinessDocumentHeader');

        // Find the DocumentIdentification element
        $businessScopes = $xpath->query('//ns:BusinessScope/ns:Scope');

        foreach ($businessScopes as $scope)
        {
            // Find the Type element within the current Scope
            $type = $scope->getElementsByTagName('Type')->item(0);

            $this->_log->log("Type node value:          $type->nodeValue");

            $identifier         = $scope->getElementsByTagName('Identifier')->item(0);
            $instanceIdentifier = $scope->getElementsByTagName('InstanceIdentifier')->item(0);

            switch ($type->nodeValue)
            {
                case "DOCUMENTID":  $instanceIdentifier->nodeValue = $documentIdInstanceIdentifier;break;
                case "PROCESSID":
                {
                    if($glnCh === NetworkType::PEPPOL_AS4)
                        $identifier->nodeValue          = "cenbii-procid-ubl";
                    $instanceIdentifier->nodeValue  = $processIdInstanceIdentifier;
                    break;
                }
                case "COUNTRY_C1":  $instanceIdentifier->nodeValue = $countryC1InstanceIdentifier;break;
            }
        }
    }
    private function setSbdSenderAndReceiver(
        string    $senderIdentifier,
        string    $receiverIdentifier)
    {
        $senderIdentifierArr    = explode(":", $senderIdentifier);
        $receiverIdentifierArr  = explode(":", $receiverIdentifier);
        if(($senderIdentifierArr[0] == EAS::CVR) || ($senderIdentifierArr[0] == EAS::SE))
        {
            $senderIdentifierArr[1] = preg_replace('/\D/', '', $senderIdentifierArr[1]);
            $senderIdentifier       = "{$senderIdentifierArr[0]}:{$senderIdentifierArr[1]}";
        }
        if(($receiverIdentifierArr[0] == EAS::CVR) || ($receiverIdentifierArr[0] == EAS::SE))
        {
            $receiverIdentifierArr[1]   = preg_replace('/\D/', '', $receiverIdentifierArr[1]);
            $receiverIdentifier         = "{$receiverIdentifierArr[0]}:{$receiverIdentifierArr[1]}";
        }

        $xpath = new DOMXPath($this->_sbdTemplateDocument);
        $xpath->registerNamespace('ns', 'http://www.unece.org/cefact/namespaces/StandardBusinessDocumentHeader');

        // Find the Sender element
        $senderNode = $xpath->query('//ns:Sender')->item(0);

        if ($senderNode instanceof DOMElement)
        {
            $sender = $senderNode;
            $sender->getElementsByTagName('Identifier')
                ->item(0)->nodeValue = $senderIdentifier;
        }

        // Find the Receiver element
        $receiverNode = $xpath->query('//ns:Receiver')->item(0);

        if ($receiverNode instanceof DOMElement)
        {
            $receiver = $receiverNode;
            $receiver->getElementsByTagName('Identifier')
                ->item(0)->nodeValue = $receiverIdentifier;
        }
    }

    private function setSbdPayload(): string
    {
        // Find the location where you want to insert the new element (e.g., a specific parent element)
        $payloadElement = $this->_sbdTemplateDocument->getElementsByTagName('Payload')->item(0);

        // Replace the <Payload> element with the contents of the replacement XML
        if ($payloadElement && $this->_payloadDocument->documentElement)
        {
            $importedNode = $this->_sbdTemplateDocument->importNode($this->_payloadDocument->documentElement, true);
            $payloadElement->parentNode->replaceChild($importedNode, $payloadElement);
        }

        // Get the modified XML content
        return $this->_sbdTemplateDocument->saveXML();
    }


    /**
     * @throws Exception
     */
    private function generateUUID($postFix=""): string
    {
        $UUID   = sprintf("%04x%04x-%04x-%04x-%04x-%04x%04x%04x$postFix",
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        $this->_log->log("Generated UUID:           $UUID", Logger::LV_2);
        return $UUID;
    }
}

