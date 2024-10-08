<?php

namespace Fakturaservice\Edelivery;

use DOMDocument;
use DOMElement;
use DOMException;
use DOMXPath;
use Fakturaservice\Edelivery\OIOUBL\{
    CustomizationID,
    EAS,
    EndpointID,
    ICD,
    ProfileID,
    UNCL1001
};
use Fakturaservice\Edelivery\util\LoggerInterface;


class Converter
{
    private string $_className;
    private LoggerInterface $_log;

    public function __construct(LoggerInterface $logger)
    {
        $this->_className           = basename(str_replace('\\', '/', get_called_class()));
        $this->_log                 = $logger;
        $this->_log->setChannel($this->_className);
    }

    /**
     * @throws DOMException
     */
    public function OIOUBLtoBIS3(string $inputXml): string
    {
        $inputXml = $this->ensureXmlDeclarationString($inputXml);

        // Load XML string into DOMDocument
        $dom = new DOMDocument;
        $dom->loadXML($inputXml);

        $this->removeUBLVersionID($dom);
        $this->convertCustomizationID($dom);
        $this->convertProfileID($dom);
        $this->removeCopyIndicator($dom);
        $this->removeUUID($dom);
        $this->movePaymentDueDate($dom);
        $this->convertDocTypeCode($dom);
        $this->ensureOrderReference($dom);
        $this->removeAdditionalDocumentReferenceAttributes($dom);
        $this->handleRegistrationName($dom);
        $this->removeDeliveryLocationDescription($dom);
        $this->removeWebsiteURI($dom);
        $this->removeAddressFormatCode($dom);
        $this->updateOrderReference($dom);
        $this->removeLanguageID($dom);
        $this->removeEmptyBuildingNumbers($dom);
        $this->adjustPriceAndQuantityOnInvoice($dom);
        $this->adjustPriceAndQuantityOnCreditNote($dom);
//        $this->removePartyIdentificationSchemeID($dom);
        $this->removePartyTaxSchemeCompanyIDScheme($dom);
        $this->updateTaxSchemeID($dom);
        $this->removeSchemeAgencyIDAttributes($dom);
        $this->removeNameTags($dom);
        $this->convertAllSchemeIDsAndCleanUpEndpointID($dom);
        $this->insertPartyTaxScheme($dom);
        $this->insertPartyLegalEntity($dom);
        $this->removeSupplierAssignedAccountID($dom);
        $this->removeOtherCommunication($dom);
        $this->removeContactID($dom);

        $this->ensureConsistentPaymentMeansCode($dom);
        $this->modifyPaymentMeansCode93OR50($dom);
        $this->removePaymentMeansID($dom);

        $this->removePaymentChannelCode($dom);
        $this->overwritePaymentTerms($dom);
        $this->copyLineExtensionAmountToTaxExclusiveAmount($dom);
        $this->transformInvoiceLines($dom);
        $this->removePaymentNote($dom);
        $this->updateFinancialInstitution($dom);
        $this->removeOrderableUnitFactorRate($dom);
        $this->removeEmptyElements($dom);
//        $this->removeZeroTaxableAmount($dom);
        $this->removeOrderReference($dom);
        $dom = $this->removeNamespaceDeclarationAttributes($dom);
        $this->ensureCorrectTaxTotal($dom);

        // Convert DOMDocument back to XML string
        $outputXml = $dom->saveXML();
        $outputXml = preg_replace('/(<c[b|a]c:[a-zA-Z]+)(\s*xmlns:(cbc|cac)=\"urn:oasis:names:specification:ubl:schema:xsd:(CommonBasicComponents-2|CommonAggregateComponents-2)\"){1,2}(>)/', '$1$5', $outputXml);
        $outputXml = preg_replace('/(\n\s*)+/', "\n", $outputXml);

        return $outputXml;
    }


    private function ensureXmlDeclarationString(string $inputXml): string {
        // Check if the XML declaration is already present
        if (preg_match('/<\?xml [^>]*\?>/', $inputXml)) {
            // If present, ensure it has the correct attributes
            return preg_replace('/<\?xml [^>]*\?>/', '<?xml version="1.0" encoding="utf-8" standalone="yes"?>', $inputXml, 1);
        } else {
            // If not present, insert the XML declaration at the beginning
            return '<?xml version="1.0" encoding="utf-8" standalone="yes"?>' . "\n" . $inputXml;
        }
    }

    private function removeSchemeAgencyIDAttributes(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Query for all elements with 'schemeAgencyID' attribute
        $elementsWithSchemeAgencyID = $xpath->query('//*[@schemeAgencyID]');

        // Loop through each element and remove the 'schemeAgencyID' attribute
        foreach ($elementsWithSchemeAgencyID as $element) {
            $element->removeAttribute('schemeAgencyID');
        }
    }

    private function removeUBLVersionID(DOMDocument $dom)
    {
        $ublVersionID = $dom->getElementsByTagName('UBLVersionID')->item(0);
        if ($ublVersionID instanceof DOMElement)
        {
            $this->_log->log("Removing UBL version ID: " . $ublVersionID->nodeValue);
            $ublVersionID->parentNode->removeChild($ublVersionID);
        }
    }

    private function convertCustomizationID(DOMDocument $dom)
    {
        $customizationID = $dom->getElementsByTagName('CustomizationID')->item(0);
        $customizationID->nodeValue = CustomizationID::peppol_poacc_billing_3_0;//'urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0';
        $this->_log->log("Converting CustomizationID to: " . $customizationID->nodeValue);
    }

    private function convertProfileID(DOMDocument $dom)
    {
        $profileID = $dom->getElementsByTagName('ProfileID')->item(0);
        if ($profileID instanceof DOMElement)
        {
            $profileID->nodeValue = ProfileID::peppol_poacc_billing_01_1_0;// 'urn:fdc:peppol.eu:2017:poacc:billing:01:1.0';
            $profileID->removeAttribute('schemeAgencyID');
            $profileID->removeAttribute('schemeID');
            $this->_log->log("Converting profileID to: " . $profileID->nodeValue);
        }
    }

    private function removeCopyIndicator(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Find all CopyIndicator elements
        $copyIndicatorNodes = $xpath->query('//cbc:CopyIndicator');

        foreach ($copyIndicatorNodes as $copyIndicatorNode) {
            // Remove each CopyIndicator element
            $copyIndicatorNode->parentNode->removeChild($copyIndicatorNode);
            $this->_log->log("Removing copyIndicator: " . $copyIndicatorNode->nodeName);
        }
    }

    private function removeUUID(DOMDocument $dom)
    {
        $uuid = $dom->getElementsByTagName('UUID')->item(0);
        if ($uuid instanceof DOMElement)
        {
            $uuid->parentNode->removeChild($uuid);
            $this->_log->log("Removing UUID: " . $uuid->nodeValue);
        }
    }
    /**
     * @throws DOMException
     */
    private function movePaymentDueDate(DOMDocument $dom)
    {
        $paymentDueDate = $dom->getElementsByTagName('PaymentDueDate')->item(0);

        if ($paymentDueDate instanceof DOMElement)
        {
            $newDueDate = $dom->createElement('cbc:DueDate', $paymentDueDate->nodeValue);

            // Insert the new DueDate after the IssueDate
            $issueDate = $dom->getElementsByTagName('IssueDate')->item(0);
            $issueDate->parentNode->insertBefore($newDueDate, $issueDate->nextSibling);
            $this->_log->log("Moving Payment DueDate before IssueDate: " . $issueDate->nodeValue);

            // Remove the original PaymentDueDate
            $paymentDueDate->parentNode->removeChild($paymentDueDate);
        }
    }

    /**
     * @param DOMDocument $dom
     * @return void
     * @throws DOMException
     */
    private function convertDocTypeCode(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $rootElement = $dom->documentElement->localName;

        if ($rootElement === 'Invoice') {
            // Check if InvoiceTypeCode exists
            $invoiceTypeCode = $xpath->query('//cbc:InvoiceTypeCode');

            if ($invoiceTypeCode->length > 0 && $invoiceTypeCode->item(0) instanceof DOMElement) {
                $invoiceTypeElement = $invoiceTypeCode->item(0);
            } else {
                // Create InvoiceTypeCode element if it doesn't exist
                $invoiceTypeElement = $dom->createElement('cbc:InvoiceTypeCode');
                // Find cbc:DocumentCurrencyCode
                $documentCurrencyCode = $xpath->query('//cbc:DocumentCurrencyCode');

                if ($documentCurrencyCode->length > 0 && $documentCurrencyCode->item(0) instanceof DOMElement) {
                    $documentCurrencyCodeParent = $documentCurrencyCode->item(0)->parentNode;
                    $documentCurrencyCodeParent->insertBefore($invoiceTypeElement, $documentCurrencyCode->item(0));
                } else {
                    // Append at the end if DocumentCurrencyCode not found
                    $dom->documentElement->appendChild($invoiceTypeElement);
                }
            }

            // Set the value to 380 if it's not already
            $invoiceTypeElement->nodeValue = UNCL1001::_380;
            $this->_log->log("Converting invoiceTypeCode to: " . $invoiceTypeElement->nodeValue);

            // Remove attributes
            if ($invoiceTypeElement instanceof DOMElement) {
                $invoiceTypeElement->removeAttribute('listAgencyID');
                $invoiceTypeElement->removeAttribute('listID');
            }
        } elseif ($rootElement === 'CreditNote') {
            // Check if CreditNoteTypeCode exists
            $creditNoteTypeCode = $xpath->query('//cbc:CreditNoteTypeCode');

            if ($creditNoteTypeCode->length > 0 && $creditNoteTypeCode->item(0) instanceof DOMElement) {
                $creditNoteTypeElement = $creditNoteTypeCode->item(0);
            } else {
                // Create CreditNoteTypeCode element if it doesn't exist
                $creditNoteTypeElement = $dom->createElement('cbc:CreditNoteTypeCode');

                // Find cbc:Note and cbc:DocumentCurrencyCode
                $note                   = $xpath->query('//cbc:Note');
                $documentCurrencyCode   = $xpath->query('//cbc:DocumentCurrencyCode');

                if ($note->length > 0 && $note->item(0) instanceof DOMElement) {
                    $noteParent = $note->item(0)->parentNode;
                    $noteParent->insertBefore($creditNoteTypeElement, $note->item(0));
                }
                elseif ($documentCurrencyCode->length > 0 && $documentCurrencyCode->item(0) instanceof DOMElement) {
                    $documentCurrencyCodeParent = $documentCurrencyCode->item(0)->parentNode;
                    $documentCurrencyCodeParent->insertBefore($creditNoteTypeElement, $documentCurrencyCode->item(0));
                } else {
                    // Append at the end if DocumentCurrencyCode and Note not found
                    $dom->documentElement->appendChild($creditNoteTypeElement);
                }
            }

            // Set the value to 380 if it's not already
            $creditNoteTypeElement->nodeValue = UNCL1001::_381;
            $this->_log->log("Converting creditNoteTypeCode to: " . $creditNoteTypeElement->nodeValue);

            // Remove attributes
            if ($creditNoteTypeElement instanceof DOMElement) {
                $creditNoteTypeElement->removeAttribute('listAgencyID');
                $creditNoteTypeElement->removeAttribute('listID');
            }
        }
    }

    /**
     * @throws DOMException
     */
    private function ensureOrderReference(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);

        // Check if BuyerReference or OrderReference/ID is present
        $buyerReference = $xpath->query('//cbc:BuyerReference');
        $orderReferenceId = $xpath->query('//cac:OrderReference/cbc:ID');

        if ($buyerReference->length === 0 && $orderReferenceId->length === 0) {
            // If neither is present, create OrderReference/ID with the value '-'
            $orderReference = $dom->createElement('cac:OrderReference');
            $orderId = $dom->createElement('cbc:ID', '-');
            $orderReference->appendChild($orderId);

            // Insert after DocumentCurrencyCode
            $documentCurrencyCode = $xpath->query('//cbc:DocumentCurrencyCode')->item(0);
            if ($documentCurrencyCode instanceof DOMElement) {
                $documentCurrencyCode->parentNode->insertBefore($orderReference, $documentCurrencyCode->nextSibling);
                $this->_log->log("Inserting orderReference before DocumentCurrencyCode: " . $orderReference->nodeValue);
            }
        }
    }
    public function removeAdditionalDocumentReferenceAttributes(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

        // Check if AdditionalDocumentReference is present
        $additionalDocumentReferences = $xpath->query('//cac:AdditionalDocumentReference');

        foreach ($additionalDocumentReferences as $additionalDocumentReference) {
            // Remove DocumentType from AdditionalDocumentReference
            $documentType = $xpath->query('cbc:DocumentType', $additionalDocumentReference);

            if ($documentType->length > 0) {
                $documentType->item(0)->parentNode->removeChild($documentType->item(0));
                $this->_log->log("Removing additionalDocumentReference: " . $additionalDocumentReference->nodeName);
            }

            // Remove unnecessary attributes from Attachment/EmbeddedDocumentBinaryObject
            $embeddedDocumentBinaryObject = $xpath->query('cac:Attachment/cbc:EmbeddedDocumentBinaryObject', $additionalDocumentReference);

            if ($embeddedDocumentBinaryObject->length > 0) {
                $embeddedDocumentBinaryObject = $embeddedDocumentBinaryObject->item(0);
                // Explicitly cast to DOMElement to access setAttribute method
                if ($embeddedDocumentBinaryObject instanceof DOMElement) {
                    // Set exact attributes
                    $embeddedDocumentBinaryObject->setAttribute('filename', 'attachment.pdf');
                    $embeddedDocumentBinaryObject->setAttribute('mimeCode', 'application/pdf');

                    $this->_log->log("Setting attributes ('filename' and 'mimeCode') on: " . $embeddedDocumentBinaryObject->nodeName);

                    // Remove other attributes
                    $attributesToRemove = ['encodingCode', 'uri', 'description', 'characterSetCode'];
                    foreach ($attributesToRemove as $attribute) {
                        if ($embeddedDocumentBinaryObject->hasAttribute($attribute)) {
                            $embeddedDocumentBinaryObject->removeAttribute($attribute);
                            $this->_log->log("Removing attribute: " . $attribute);
                        }
                    }
                }
            }
        }
    }

    /**
     * @throws DOMException
     */
    private function handleRegistrationName(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Check if cac:AccountingSupplierParty/cac:Party/cac:PartyLegalEntity/cbc:RegistrationName is empty or not present
        $registrationNameNode = $xpath->query('//cac:AccountingSupplierParty/cac:Party/cac:PartyLegalEntity/cbc:RegistrationName')->item(0);

        if (!$registrationNameNode || empty($registrationNameNode->nodeValue)) {
            // Create the missing cac:AccountingSupplierParty/cac:Party/cac:PartyLegalEntity/cbc:RegistrationName
            $registrationNameElement = $dom->createElement('cbc:RegistrationName');

            // Get the value from cac:AccountingSupplierParty/cac:Party/cac:PartyName/cbc:Name
            $partyNameNode = $xpath->query('//cac:AccountingSupplierParty/cac:Party/cac:PartyName/cbc:Name')->item(0);

            if ($partyNameNode) {
                // Create a text node with the value and append it to the element
                $textNode = $dom->createTextNode($partyNameNode->nodeValue);
                $registrationNameElement->appendChild($textNode);
                $this->_log->log("Adding to registrationName: " . $textNode->nodeValue);

                // Insert the created element as the first child of cac:PartyLegalEntity
                $partyLegalEntityNode = $xpath->query('//cac:AccountingSupplierParty/cac:Party/cac:PartyLegalEntity')->item(0);

                if ($partyLegalEntityNode instanceof DOMElement) {
                    // Check if there are existing children, and insert before the first one if any
                    if ($partyLegalEntityNode->hasChildNodes()) {
                        $firstChild = $partyLegalEntityNode->firstChild;
                        $partyLegalEntityNode->insertBefore($registrationNameElement, $firstChild);
                    } else {
                        $partyLegalEntityNode->appendChild($registrationNameElement);
                    }
                }
            }
        }
    }

    private function removeDeliveryLocationDescription(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Check if cac:Delivery/cac:DeliveryLocation/cbc:Description exists
        $descriptionNode = $xpath->query('//cac:Delivery/cac:DeliveryLocation/cbc:Description')->item(0);

        if ($descriptionNode instanceof DOMElement) {
            // Remove the description element
            $descriptionNode->parentNode->removeChild($descriptionNode);
        }
    }

    private function removeAddressFormatCode(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);
        $addressFormatCodes = $xpath->query('//cbc:AddressFormatCode');

        foreach ($addressFormatCodes as $addressFormatCode) {
            $addressFormatCode->parentNode->removeChild($addressFormatCode);
        }
    }

    /**
     * @throws DOMException
     */
    private function updateOrderReference(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Remove cac:OrderReference/cbc:IssueDate
        $issueDateNodes = $xpath->query('//cac:OrderReference/cbc:IssueDate');
        foreach ($issueDateNodes as $issueDateNode) {
            $issueDateNode->parentNode->removeChild($issueDateNode);
        }

        // Rename cac:OrderReference/cbc:CustomerReference to cac:OrderReference/cbc:SalesOrderID
        $customerReferenceNodes = $xpath->query('//cac:OrderReference/cbc:CustomerReference');
        foreach ($customerReferenceNodes as $customerReferenceNode) {
            // Create a new SalesOrderID element
            $salesOrderIDNode = $dom->createElement('cbc:SalesOrderID');

            // Copy the value from CustomerReference to SalesOrderID
            $salesOrderIDNode->nodeValue = $customerReferenceNode->nodeValue;

            // Replace the CustomerReference node with SalesOrderID
            $customerReferenceNode->parentNode->replaceChild($salesOrderIDNode, $customerReferenceNode);
        }


    }

    private function removeEmptyElements(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);

        while (($node_list = $xpath->query('//*[not(*) and not(@*) and not(text()[normalize-space()])]')) && $node_list->length) {
            foreach ($node_list as $node) {
                $node->parentNode->removeChild($node);
            }
        }
    }

    private function removeEmptyBuildingNumbers(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);

        // Remove empty BuildingNumber tags
        $buildingNumbers = $xpath->query('//cbc:BuildingNumber[.="-" or .="N/A" or not(normalize-space(.))]');

        foreach ($buildingNumbers as $buildingNumber) {
            $buildingNumber->parentNode->removeChild($buildingNumber);
        }
    }

    private function removePartyIdentificationSchemeID(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

        $partyIdentifications = $xpath->query('//cac:PartyIdentification/cbc:ID');

        foreach ($partyIdentifications as $idElement) {
            $schemeID   = $idElement->getAttribute('schemeID');
            $idValue    = $idElement->nodeValue;

            // Check if the schemeID attribute is present
            if ($schemeID !== '') {
                // Remove the schemeID attribute
                $idElement->removeAttribute('schemeID');
            }

            // Check if the ID value contains ":"
            if (strpos($idValue, ":") !== false) {
                // Remove everything before ":"
                $newIdValue = substr($idValue, strpos($idValue, ":") + 1);

                // Update the node value
                $idElement->nodeValue = $newIdValue;
            }
        }
    }


    private function removePartyTaxSchemeCompanyIDScheme(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

        $partyTaxSchemes = $xpath->query('//cac:PartyTaxScheme');

        foreach ($partyTaxSchemes as $taxScheme) {
            $companyID = $xpath->query('cbc:CompanyID', $taxScheme)->item(0);

            if ($companyID instanceof DOMElement) {
                $schemeID = $companyID->getAttribute('schemeID');

                // Check if the parent node is cac:PartyTaxScheme, and schemeID is present
                if ($companyID->parentNode->nodeName === 'cac:PartyTaxScheme' && $schemeID !== '') {
                    // Remove the schemeID attribute
                    $companyID->removeAttribute('schemeID');
                }
            }
        }
    }

    private function updateTaxSchemeID(DOMDocument $dom) {
        $taxIDs = $dom->getElementsByTagName('ID');

        foreach ($taxIDs as $taxID)
        {
//            $schemeAgencyID = $taxID->getAttribute('schemeAgencyID');
            $schemeID = $taxID->getAttribute('schemeID');
            $value = $taxID->nodeValue;

            // Check if the attributes match the specified criteria
            if (($schemeID === 'urn:oioubl:id:taxschemeid-1.1') || ($schemeID === 'urn:oioubl:id:taxcategoryid-1.1'))
            {
                // Change the value based on specified mappings
                switch ($value) {
                    case 'StandardRated':
                        $taxID->nodeValue = 'S';
                        break;
                    case 'ZeroRated':
                        $taxID->nodeValue = 'Z';
                        break;
                    case 'ReverseCharge':
                        $taxID->nodeValue = 'AE';
                        break;
                    case '63':
                        $taxID->nodeValue = 'VAT';
                        break;
                    // Add more cases for other values if needed
                }

                // Remove schemeAgencyID and schemeID attributes
                $taxID->removeAttribute('schemeAgencyID');
                $taxID->removeAttribute('schemeID');
            }
        }
    }

    private function removeNameTags(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);
        $taxSchemeNames = $xpath->query('//cac:TaxScheme/cbc:Name[text()="Moms"]/../..//cbc:Name');

        foreach ($taxSchemeNames as $taxSchemeName) {
            $taxSchemeName->parentNode->removeChild($taxSchemeName);
        }
    }

    public function convertAllSchemeIDsAndCleanUpEndpointID(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $nodesWithSchemeID = $xpath->query('//*[@schemeID]');

        foreach ($nodesWithSchemeID as $node) {
            // Get schemeID attribute
            $schemeID           = $node->getAttribute('schemeID');
            $endpointId         = explode(':', $node->nodeValue);
            $node->nodeValue    = preg_replace('/\d+:/', '', $node->nodeValue);

            if(($endpointId[0] === ICD::FI_OVT) && ($schemeID === EndpointID::FI_OVT))
                $node->nodeValue = ICD::FI_OVT . ":{$node->nodeValue}";
            if(
                ($endpointId[0] === EAS::CVR) ||
                ($schemeID === EndpointID::DK_CVR) ||
                ($endpointId[0] === EAS::SE) ||
                ($schemeID === EndpointID::DK_SE)
            )
            {
                $node->nodeValue    = preg_replace('/\D/', '', $node->nodeValue);
                $node->nodeValue    = "DK{$node->nodeValue}";
            }
            if(count($endpointId) > 1)
                $node->setAttribute('schemeID', $endpointId[0]);
            else
                $node->setAttribute('schemeID', EAS::getId($schemeID));
        }
    }

    private function removeLanguageID(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);
        $languageIDs = $xpath->query('//cac:Language/cbc:ID');

        foreach ($languageIDs as $languageID)
        {
            $languageID->parentNode->parentNode->removeChild($languageID->parentNode);
        }
    }

    /**
     * @throws DOMException
     */
    private function insertPartyTaxScheme(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Check if PartyTaxScheme is present
        $partyTaxScheme = $xpath->query('//cac:AccountingCustomerParty/cac:Party/cac:PartyTaxScheme');

        // Get the values of EndpointID and PartyIdentificationID
        $endpointID                 = $xpath->evaluate('string(//cac:AccountingCustomerParty/cac:Party/cbc:EndpointID)');
        $partyIdentificationID      = $xpath->evaluate('string(//cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID)');
        $countryIdentificationCode  = $xpath->evaluate('string(//cac:AccountingCustomerParty/cac:Party/cac:PostalAddress/cac:Country/cbc:IdentificationCode)');

        // Check if PartyTaxScheme is not present and EndpointID is different from PartyIdentificationID
        if ($partyTaxScheme->length === 0 && $endpointID !== $partyIdentificationID) {
            // Prefix PartyIdentificationID if necessary
            if (strpos($partyIdentificationID, $countryIdentificationCode) !== 0) {
                $partyIdentificationID = $countryIdentificationCode . $partyIdentificationID;
            }

            // Insert PartyTaxScheme after cac:PostalAddress
            $accountingCustomerParty = $xpath->query('//cac:AccountingCustomerParty/cac:Party')->item(0);

            if ($accountingCustomerParty instanceof DOMElement) {
                $postalAddress = $xpath->query('cac:PostalAddress', $accountingCustomerParty)->item(0);

                if ($postalAddress instanceof DOMElement) {
                    // Create new PartyTaxScheme block
                    $newPartyTaxScheme = $dom->createElement('cac:PartyTaxScheme');
                    $newCompanyID = $dom->createElement('cbc:CompanyID', $partyIdentificationID);
                    $newTaxScheme = $dom->createElement('cac:TaxScheme');
                    $newTaxScheme->appendChild($dom->createElement('cbc:ID', 'VAT'));

                    // Append elements to PartyTaxScheme
                    $newPartyTaxScheme->appendChild($newCompanyID);
                    $newPartyTaxScheme->appendChild($newTaxScheme);

                    // Insert PartyTaxScheme after cac:PostalAddress
                    $accountingCustomerParty->insertBefore($newPartyTaxScheme, $postalAddress->nextSibling);
                }
            }
        }
    }



//    private function insertPartyTaxScheme(DOMDocument $dom)
//    {
//        $xpath = new DOMXPath($dom);
//
//        // Register namespaces
////        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
////        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
//
//        // Find all AccountingCustomerParty and AccountingSupplierParty elements
//        $parties = $xpath->query('//cac:AccountingCustomerParty/cac:Party | //cac:AccountingSupplierParty/cac:Party');
//
//        foreach ($parties as $party) {
//            // Check if PartyTaxScheme is present
//            $partyTaxScheme = $xpath->query('cac:PartyTaxScheme', $party);
//
//            // Get the values of EndpointID and PartyIdentificationID
//            $endpointID                = $xpath->evaluate('string(cbc:EndpointID)', $party);
//            $partyIdentificationID     = $xpath->evaluate('string(cac:PartyIdentification/cbc:ID)', $party);
//            $countryIdentificationCode = $xpath->evaluate('string(cac:PostalAddress/cac:Country/cbc:IdentificationCode)', $party);
//
//            // Check if PartyTaxScheme is not present and EndpointID is different from PartyIdentificationID
//            if ($partyTaxScheme->length === 0 && $endpointID !== $partyIdentificationID) {
//                // Prefix PartyIdentificationID if necessary
//                if (strpos($partyIdentificationID, $countryIdentificationCode) !== 0) {
//                    $partyIdentificationID = $countryIdentificationCode . $partyIdentificationID;
//                }
//
//                // Insert PartyTaxScheme after cac:PostalAddress
//                $postalAddress = $xpath->query('cac:PostalAddress', $party)->item(0);
//
//                if ($postalAddress instanceof DOMElement) {
//                    // Create new PartyTaxScheme block
//                    $newPartyTaxScheme = $dom->createElement('cac:PartyTaxScheme');
//                    $newCompanyID = $dom->createElement('cbc:CompanyID', $partyIdentificationID);
//                    $newTaxScheme = $dom->createElement('cac:TaxScheme');
//                    $newTaxScheme->appendChild($dom->createElement('cbc:ID', 'VAT'));
//
//                    // Append elements to PartyTaxScheme
//                    $newPartyTaxScheme->appendChild($newCompanyID);
//                    $newPartyTaxScheme->appendChild($newTaxScheme);
//
//                    // Insert PartyTaxScheme after cac:PostalAddress
//                    $party->insertBefore($newPartyTaxScheme, $postalAddress->nextSibling);
//                }
//            }
//        }
//    }




    /**
     * @throws DOMException
     */
    private function insertPartyLegalEntity(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Check if PartyLegalEntity is present
        $partyLegalEntity = $xpath->query('//cac:AccountingCustomerParty/cac:Party/cac:PartyLegalEntity');

        // Check if the attribute 'schemeID' in cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID is not equal "0088"
        $partyIdentificationSchemeID = $xpath->evaluate('string(//cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID/@schemeID)');
        if ($partyLegalEntity->length === 0) {
            // If not present and schemeID is not "0088", insert PartyLegalEntity before cac:Contact
            $accountingCustomerParty = $xpath->query('//cac:AccountingCustomerParty/cac:Party')->item(0);

            if ($accountingCustomerParty instanceof DOMElement) {

//                $accountingCustomerParty->nodeValue = urlencode($accountingCustomerParty->nodeValue);
                // Create new PartyLegalEntity block
                $newPartyLegalEntity = $dom->createElement('cac:PartyLegalEntity');
                $newRegistrationName = $dom->createElement('cbc:RegistrationName', htmlspecialchars($xpath->evaluate('string(//cac:AccountingCustomerParty/cac:Party/cac:PartyName/cbc:Name)', $accountingCustomerParty)));
                // Append RegistrationName to PartyLegalEntity
                $newPartyLegalEntity->appendChild($newRegistrationName);

                // Get the values of EndpointID and PartyIdentificationID
                $endpointID             = $xpath->evaluate('string(//cac:AccountingCustomerParty/cac:Party/cbc:EndpointID)');
                $partyIdentificationID  = $xpath->evaluate('string(//cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID)');

                if(($partyIdentificationSchemeID !== ICD::GLN) && ($endpointID !== $partyIdentificationID)) {
                    $newCompanyID = $dom->createElement('cbc:CompanyID', $xpath->evaluate('string(//cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID)', $accountingCustomerParty));
                    $newPartyLegalEntity->appendChild($newCompanyID);
                }

                //Looking fo Belgian Endpoint
                if (stripos($endpointID, "be") === 0 && strpos($partyIdentificationID, substr($endpointID, 2)) !== false)
                {
                    $this->_log->log("Belgian PartyIdentification detected");
                    $partyIdentification    = substr($endpointID, 2);
                    $partyIdentificationElement = $xpath->query('//cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID')->item(0);
                    if ($partyIdentificationElement instanceof DOMElement) {
                        $this->_log->log("Setting nodeValue to: '$partyIdentification'");
                        $partyIdentificationElement->nodeValue = $partyIdentification;
                        $this->_log->log("Setting 'schemeID' to: '0208'");
                        $partyIdentificationElement->setAttribute('schemeID', '0208');
                    }
                }

                // Find cac:Contact
                $contact = $xpath->query('cac:Contact', $accountingCustomerParty)->item(0);

                // Insert PartyLegalEntity before cac:Contact
                if ($contact instanceof DOMElement) {
                    $accountingCustomerParty->insertBefore($newPartyLegalEntity, $contact);
                } else {
                    // If cac:Contact is not present, append PartyLegalEntity to the end
                    $accountingCustomerParty->appendChild($newPartyLegalEntity);
                }
            }
        }
        elseif ($partyLegalEntity->length > 0) {
            // Check if PartyLegalEntity has RegistrationName and/or CompanyID
            $existingPartyLegalEntity = $partyLegalEntity->item(0);
            $registrationName = $xpath->query('cbc:RegistrationName', $existingPartyLegalEntity);
            $companyID = $xpath->query('cbc:CompanyID[@schemeID="ZZZ" or @schemeID="DK:CVR"]', $existingPartyLegalEntity);

            if ($registrationName->length > 0 || $companyID->length > 0) {
                // Get the value of 'cac:AccountingCustomerParty/cac:Party/cac:PostalAddress/cac:Country/cbc:IdentificationCode'
                $identificationCode = $xpath->evaluate('string(//cac:AccountingCustomerParty/cac:Party/cac:PostalAddress/cac:Country/cbc:IdentificationCode)');

                // Set schemeID based on IdentificationCode
                if ($companyID->length > 0) {//FIXME                                HACK: Guessing ICD from cac:Country/cbc:IdentificationCode
                    $companyID->item(0)->setAttribute('schemeID', ICD::getIdsByAlpha2Code($identificationCode)[0]);
                }
            }
        }
    }

    private function removeSupplierAssignedAccountID(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Target cac:AccountingCustomerParty/cbc:SupplierAssignedAccountID
        $elementsToRemove = $xpath->query('//cac:AccountingCustomerParty/cbc:SupplierAssignedAccountID');

        foreach ($elementsToRemove as $element) {
            // Remove the found element
            $element->parentNode->removeChild($element);
        }
    }

    private function removeOtherCommunication(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Target cac:AccountingCustomerParty/cac:Party/cac:Contact/cac:OtherCommunication
        $elementsToRemove = $xpath->query('//cac:AccountingCustomerParty/cac:Party/cac:Contact/cac:OtherCommunication');

        foreach ($elementsToRemove as $element) {
            // Remove the found element
            $element->parentNode->removeChild($element);
        }
    }

    private function removeContactID(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);

        // Remove cbc:ID in AccountingCustomerParty/Party/Contact
        $customerContactID = $xpath->query('//cac:AccountingCustomerParty/cac:Party/cac:Contact/cbc:ID');
        foreach ($customerContactID as $idElement) {
            $idElement->parentNode->removeChild($idElement);
        }

        // Remove cbc:ID in AccountingSupplierParty/Party/Contact
        $supplierContactID = $xpath->query('//cac:AccountingSupplierParty/cac:Party/cac:Contact/cbc:ID');
        foreach ($supplierContactID as $idElement) {
            $idElement->parentNode->removeChild($idElement);
        }
    }

    private function ensureConsistentPaymentMeansCode(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Find all PaymentMeans elements
        $paymentMeansNodes = $xpath->query('//cac:PaymentMeans');
        if ($paymentMeansNodes->length > 1) {
            // Get the PaymentMeansCode of the first PaymentMeans element
            $firstPaymentMeansCode = $xpath->evaluate('string(cbc:PaymentMeansCode)', $paymentMeansNodes->item(0));

            // Iterate through all PaymentMeans elements starting from the second one
            for ($i = 1; $i < $paymentMeansNodes->length; $i++) {
                $paymentMeansCode = $xpath->evaluate('string(cbc:PaymentMeansCode)', $paymentMeansNodes->item($i));

                // If the PaymentMeansCode is different, remove the element
                if ($paymentMeansCode !== $firstPaymentMeansCode) {
                    $paymentMeansNodes->item($i)->parentNode->removeChild($paymentMeansNodes->item($i));
                    $this->_log->log("Removing PaymentMeansCode: '$paymentMeansCode'");
                }
            }
        }
    }


    /**
     * @throws DOMException
     */
    private function modifyPaymentMeansCode93OR50(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Target cac:PaymentMeans where PaymentMeansCode is 93 and PaymentID is 71, 73, or 75
        // OR where PaymentMeansCode is 50 and PaymentID is 01, 04, or 15
        $paymentMeansElements = $xpath->query('//cac:PaymentMeans[
            (cbc:PaymentMeansCode="93" and (cbc:PaymentID="71" or cbc:PaymentID="73" or cbc:PaymentID="75")) 
            or 
            (cbc:PaymentMeansCode="50" and (cbc:PaymentID="01" or cbc:PaymentID="04" or cbc:PaymentID="15"))
        ]');

        foreach ($paymentMeansElements as $paymentMeans) {
            // Remove cac:PaymentMeans/cbc:PaymentDueDate
            $paymentDueDate = $xpath->query('cbc:PaymentDueDate', $paymentMeans)->item(0);
            if ($paymentDueDate) {
                $paymentMeans->removeChild($paymentDueDate);
                $this->_log->log("Removing cbc:PaymentDueDate from cac:PaymentMeans");
            }

            // Move and concatenate InstructionID with PaymentID
            $instructionID          = $xpath->evaluate('string(cbc:InstructionID)', $paymentMeans);
            $instructionNote        = $xpath->evaluate('string(cbc:InstructionNote)', $paymentMeans);
            $selectedInstruction    = strlen($instructionID) >= strlen($instructionNote) ? $instructionID : $instructionNote;

//            $instructionID = $xpath->evaluate('string(cbc:InstructionID | cbc:InstructionNote)', $paymentMeans);

            $paymentIDNode = $xpath->query('cbc:PaymentID', $paymentMeans)->item(0);

            if ($selectedInstruction && $paymentIDNode) {
                $paymentIDNode->nodeValue .= '#' . $selectedInstruction;
                $this->_log->log("Move and concatenate InstructionID with PaymentID: $paymentIDNode->nodeValue");

                // Remove InstructionID element
                $instructionIDNode = $xpath->query('cbc:InstructionID | cbc:InstructionNote', $paymentMeans)->item(0);
                if ($instructionIDNode) {
                    $paymentMeans->removeChild($instructionIDNode);
                }
            }

            // Move AccountID to new PayeeFinancialAccount element
            $accountID = $xpath->evaluate('string(cac:CreditAccount/cbc:AccountID)', $paymentMeans);
            $paymentMeansCode = $xpath->evaluate('string(cbc:PaymentMeansCode)', $paymentMeans);

            if ($accountID) {

                if ($paymentMeansCode == "93" && strlen($accountID) != 8) {
                    $accountID = ltrim($accountID, '0');
                    $accountID = str_pad($accountID, 8, '0', STR_PAD_LEFT);
                    $this->_log->log("Adjusted AccountID length for PaymentMeansCode $paymentMeansCode: $accountID");
                }

                // Create new PayeeFinancialAccount element
                $payeeFinancialAccount = $dom->createElement('cac:PayeeFinancialAccount');
                $newAccountID = $dom->createElement('cbc:ID', $accountID);
                $payeeFinancialAccount->appendChild($newAccountID);

                // Append new PayeeFinancialAccount element to PaymentMeans
                $paymentMeans->appendChild($payeeFinancialAccount);

                // Remove the old CreditAccount element
                $creditAccount = $xpath->query('cac:CreditAccount', $paymentMeans)->item(0);
                if ($creditAccount) {
                    $paymentMeans->removeChild($creditAccount);
                }
                $this->_log->log("Move AccountID to new PayeeFinancialAccount element: $accountID");

            }

            // Adjust the length of the AccountID based on the PaymentMeansCode
            $idNode = $xpath->query('cac:PayeeFinancialAccount/cbc:ID', $paymentMeans)->item(0);

            if ($idNode && $paymentMeansCode) {
                $idValue = ltrim($idNode->nodeValue, '0');

                if ($paymentMeansCode == "50" && strlen($idValue) < 7) {
                    $idNode->nodeValue = str_pad($idValue, 7, '0', STR_PAD_LEFT);
                } elseif ($paymentMeansCode == "93" && strlen($idValue) < 8) {
                    $idNode->nodeValue = str_pad($idValue, 8, '0', STR_PAD_LEFT);
                }
                $this->_log->log("Adjusted AccountID length for PaymentMeansCode $paymentMeansCode: $idNode->nodeValue");
            }
        }
    }


    private function removePaymentMeansID(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Check if cbc:ID exists in cac:PaymentMeans
        $paymentMeansIDs = $xpath->query('//cac:PaymentMeans/cbc:ID');

        foreach ($paymentMeansIDs as $paymentMeansID) {
            // Remove each cbc:ID element
            $paymentMeansID->parentNode->removeChild($paymentMeansID);
        }
    }

    private function removePaymentChannelCode(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Check if cbc:PaymentChannelCode exists in cac:PaymentMeans
        $paymentChannelCode = $xpath->query('//cac:PaymentMeans/cbc:PaymentChannelCode');

        if ($paymentChannelCode->length > 0) {
            // Remove the cbc:ID element
            $paymentChannelCode->item(0)->parentNode->removeChild($paymentChannelCode->item(0));
        }
    }

    /**
     * @throws DOMException
     */
    private function overwritePaymentTerms(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Find all existing cac:PaymentTerms elements
        $paymentTermsList = $xpath->query('//cac:PaymentTerms');

        // If there are more than one PaymentTerms elements, remove all but the first one
        if ($paymentTermsList->length > 1) {
            // Remove all PaymentTerms elements except the first one
            for ($i = 1; $i < $paymentTermsList->length; $i++) {
                $paymentTermsList->item($i)->parentNode->removeChild($paymentTermsList->item($i));
            }
        }

        // If no PaymentTerms element exists, do nothing
        if ($paymentTermsList->length === 0) {
            return;
        }

        // Retrieve the first PaymentTerms element
        $terms = $paymentTermsList->item(0);

        // Remove all existing child elements
        while ($terms->hasChildNodes()) {
            $terms->removeChild($terms->firstChild);
        }

        // Create and append the new cbc:Note element
        $noteElement = $dom->createElement('cbc:Note', 'Net');
        $terms->appendChild($noteElement);
    }



    private function copyLineExtensionAmountToTaxExclusiveAmount(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);

        // Fetch the value of LineExtensionAmount
        $lineExtensionAmountValue = $xpath->evaluate('string(//cac:LegalMonetaryTotal/cbc:LineExtensionAmount)');

        // Find TaxExclusiveAmount
        $taxExclusiveAmount = $xpath->query('//cac:LegalMonetaryTotal/cbc:TaxExclusiveAmount')->item(0);

        if ($taxExclusiveAmount instanceof DOMElement) {
            // Set the value of TaxExclusiveAmount
            $taxExclusiveAmount->nodeValue = $lineExtensionAmountValue;
        }
    }

    /**
     * @throws DOMException
     */
    private function transformInvoiceLines(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);
        $docLines = $xpath->query('//cac:InvoiceLine|//cac:CreditNoteLine');

        foreach ($docLines as $invoiceLine) {
            // Find TaxTotal block
            $taxTotal = $xpath->query('cac:TaxTotal', $invoiceLine)->item(0);

            if ($taxTotal instanceof DOMElement) {
                // Find TaxCategory block
                $taxCategory = $xpath->query('cac:TaxSubtotal/cac:TaxCategory', $taxTotal)->item(0);

                if ($taxCategory instanceof DOMElement) {
                    // Move children of TaxCategory block under Item
                    $item = $xpath->query('cac:Item', $invoiceLine)->item(0);

                    if ($item instanceof DOMElement) {
                        $classTaxCategory = $dom->createElement('cac:ClassifiedTaxCategory');

                        // Append all children of TaxCategory to ClassifiedTaxCategory
                        foreach ($taxCategory->childNodes as $childNode) {
                            $classTaxCategory->appendChild($childNode->cloneNode(true));
                        }

                        // Remove TaxTotal block
                        $invoiceLine->removeChild($taxTotal);

                        // Append ClassifiedTaxCategory under Item
                        $item->appendChild($classTaxCategory);
                    }
                }
            }

            // Remove AdditionalInformation inside Item
            $additionalInformation = $xpath->query('cac:Item/cbc:AdditionalInformation', $invoiceLine)->item(0);

            if ($additionalInformation instanceof DOMElement) {
                $invoiceLine->getElementsByTagName('Item')->item(0)->removeChild($additionalInformation);
            }
        }
    }

    private function removePaymentNote(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);

        // Check if PaymentNote is present
        $paymentNote = $xpath->query('//cac:PaymentMeans/cac:PayeeFinancialAccount/cbc:PaymentNote');

        if ($paymentNote->length > 0) {
            // Remove PaymentNote
            $paymentNote->item(0)->parentNode->removeChild($paymentNote->item(0));
        }
    }

    /**
     * @throws DOMException
     */
    private function updateFinancialInstitution(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Check if PaymentMeansCode is 31 or 42
        $paymentMeansCode = $xpath->evaluate('string(//cac:PaymentMeans/cbc:PaymentMeansCode)');

        if ($paymentMeansCode === '31') {
            // Move cbc:ID up one scope
            $financialInstitutionID = $xpath->query('//cac:PaymentMeans/cac:PayeeFinancialAccount/cac:FinancialInstitutionBranch/cac:FinancialInstitution/cbc:ID');

            if ($financialInstitutionID->length > 0) {
                // Create a new cbc:ID element
                $newID = $dom->createElement('cbc:ID', $financialInstitutionID->item(0)->nodeValue);

                // Replace FinancialInstitution/cbc:ID with cbc:ID at a higher level
                $financialInstitutionID->item(0)->parentNode->parentNode->appendChild($newID);

                // Remove FinancialInstitution
                $financialInstitutionID->item(0)->parentNode->parentNode->removeChild($financialInstitutionID->item(0)->parentNode);
            }
        }
        else {
            // Remove entire FinancialInstitution
            $financialInstitution = $xpath->query('//cac:PaymentMeans/cac:PayeeFinancialAccount/cac:FinancialInstitutionBranch/cac:FinancialInstitution');

            if ($financialInstitution->length > 0) {
                // Remove FinancialInstitution and its children
                $financialInstitution->item(0)->parentNode->removeChild($financialInstitution->item(0));
            }
        }
    }

    private function removeOrderableUnitFactorRate(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);

        // Get the root element name from $dom
        $docType = $dom->documentElement->localName;

        // Check if OrderableUnitFactorRate is present
        $orderableUnitFactorRates = $xpath->query("//cac:{$docType}Line/cac:Price/cbc:OrderableUnitFactorRate");

        // Loop through each occurrence and remove it
        foreach ($orderableUnitFactorRates as $rate) {
            $rate->parentNode->removeChild($rate);
        }
    }

    private function removeZeroTaxableAmount(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);

        // Find TaxSubtotal elements with zero TaxableAmount
        $zeroTaxableAmountNodes = $xpath->query('//cac:TaxTotal/cac:TaxSubtotal[cbc:TaxableAmount = 0.00]');

        foreach ($zeroTaxableAmountNodes as $zeroTaxableAmountNode) {
            // Remove the TaxSubtotal element
            $zeroTaxableAmountNode->parentNode->removeChild($zeroTaxableAmountNode);
        }
    }

    private function removeWebsiteURI(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Select the WebsiteURI element in AccountingSupplierParty or AccountingCustomerParty
        $websiteURINodes = $xpath->query('(//cac:AccountingSupplierParty|//cac:AccountingCustomerParty)/cac:Party/cbc:WebsiteURI');

        foreach ($websiteURINodes as $websiteURINode) {
            // Remove the WebsiteURI element
            $websiteURINode->parentNode->removeChild($websiteURINode);
        }
    }

    private function removeOrderReference(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        // Target both cac:InvoiceLine and cac:CreditNoteLine
        $elementsToRemove = $xpath->query('(//cac:InvoiceLine|//cac:CreditNoteLine)/cac:OrderLineReference/cac:OrderReference');

        foreach ($elementsToRemove as $element) {
            // Remove the found element
            $element->parentNode->removeChild($element);
        }
    }

    /**
     * @throws DOMException
     */
    private function adjustPriceAndQuantityOnInvoice(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

        // Find all InvoiceLine elements
        $invoiceLines = $xpath->query('//cac:InvoiceLine');

        foreach ($invoiceLines as $invoiceLine) {
            // Find PriceAmount and InvoicedQuantity elements
            $priceAmount = $xpath->evaluate('string(cac:Price/cbc:PriceAmount)', $invoiceLine);
            $invoicedQuantity = $xpath->evaluate('string(cbc:InvoicedQuantity)', $invoiceLine);

            // Check if PriceAmount is negative and InvoicedQuantity is positive
            if ($priceAmount < 0 && $invoicedQuantity > 0) {
                // Adjust the polarity of PriceAmount and InvoicedQuantity
                $newPriceAmount = abs($priceAmount);
                $newInvoicedQuantity = -$invoicedQuantity;

                // Update the elements with the new values
                $priceAmountNode = $xpath->query('cac:Price/cbc:PriceAmount', $invoiceLine)->item(0);
                $priceAmountNode->nodeValue = $newPriceAmount;

                $invoicedQuantityNode = $xpath->query('cbc:InvoicedQuantity', $invoiceLine)->item(0);
                $invoicedQuantityNode->nodeValue = $newInvoicedQuantity;
            }

            // Check if BaseQuantity is present in cac:Price after cbc:PriceAmount
            $priceNode = $xpath->query('cac:Price', $invoiceLine)->item(0);
            $baseQuantityNode = $xpath->query('cbc:BaseQuantity', $priceNode);

            if ($baseQuantityNode->length == 0) {
                // Create and append BaseQuantity element
                $baseQuantityElement = $dom->createElement('cbc:BaseQuantity', '1');
                $baseQuantityElement->setAttribute('unitCode', 'EA');
                $priceNode->appendChild($baseQuantityElement);
                $this->_log->log("Adding cbc:BaseQuantity");
            }
        }
    }

    private function adjustPriceAndQuantityOnCreditNote(DOMDocument $dom) {
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

        // Find all CreditNoteLine elements
        $creditNoteLines = $xpath->query('//cac:CreditNoteLine');

        foreach ($creditNoteLines as $creditNoteLine) {
            // Find PriceAmount and CreditedQuantity elements
            $priceAmountNode = $xpath->query('cac:Price/cbc:PriceAmount', $creditNoteLine)->item(0);
            $creditedQuantityNode = $xpath->query('cbc:CreditedQuantity', $creditNoteLine)->item(0);

            if ($priceAmountNode && $creditedQuantityNode) {
                // Get the values
                $priceAmount = floatval($priceAmountNode->nodeValue);
                $creditedQuantity = floatval($creditedQuantityNode->nodeValue);

                // Make both PriceAmount and Quantity positive
                $newPriceAmount = abs($priceAmount);
                $newCreditedQuantity = abs($creditedQuantity);

                // Update the elements with the new values
                $priceAmountNode->nodeValue = $newPriceAmount;
                $creditedQuantityNode->nodeValue = $newCreditedQuantity;
            }
        }
    }

    /**
     * @throws DOMException
     */
    private function ensureCorrectTaxTotal(DOMDocument $dom)
    {
        $xpath = new DOMXPath($dom);

        $currencyCode = $xpath->evaluate('string(//cbc:DocumentCurrencyCode)');

        // Initialize sums for tax categories
        $taxSums = [
//            'S' => 0.00,
//            'Z' => 0.00
        ];

        // Find all cac:InvoiceLine sections
        $invoiceLines = $xpath->query('//cac:InvoiceLine|//cac:CreditNoteLine');

        $this->_log->log("Lines found: " . count($invoiceLines));

        // Loop through all cac:InvoiceLine sections
        foreach ($invoiceLines as $invoiceLine) {
            // Get tax category ID
            $taxCategoryID = $xpath->evaluate('string(cac:Item/cac:ClassifiedTaxCategory/cbc:ID)', $invoiceLine);

            // Get line extension amount
            $lineExtensionAmount = (float)$xpath->evaluate('string(cbc:LineExtensionAmount)', $invoiceLine);

            // Initialize sums for tax categories
            if (!isset($taxSums[$taxCategoryID]))
                $taxSums[$taxCategoryID] = 0.0;

            // Sum the line extension amounts for the respective tax categories
            if (isset($taxSums[$taxCategoryID])) {
                $taxSums[$taxCategoryID] += $lineExtensionAmount;
                $this->_log->log("Line extension amount on category '$taxCategoryID' => $lineExtensionAmount");
            }
        }

        // Find or create cac:TaxTotal
        $taxTotal = $xpath->query('//cac:TaxTotal')->item(0);
        if (!$taxTotal) {
            $taxTotal = $dom->createElement('cac:TaxTotal');
            $dom->documentElement->appendChild($taxTotal);
            $this->_log->log("Appending TaxTotal");
        }

        // Remove existing cac:TaxSubtotal elements
        $existingTaxSubtotals = $xpath->query('cac:TaxSubtotal', $taxTotal);
        foreach ($existingTaxSubtotals as $existingTaxSubtotal) {
            $taxTotal->removeChild($existingTaxSubtotal);
            $this->_log->log("Removing existing TaxSubtotal");
        }

        // Add new cac:TaxSubtotal elements based on calculated sums
        foreach ($taxSums as $taxCategoryID => $taxableAmount) {
//            if ($taxableAmount > 0) {
                $taxSubtotal = $dom->createElement('cac:TaxSubtotal');
                $taxTotal->appendChild($taxSubtotal);

                $taxableAmountElement = $dom->createElement('cbc:TaxableAmount', number_format($taxableAmount, 2, '.', ''));
                $taxableAmountElement->setAttribute('currencyID', $currencyCode);
                $taxSubtotal->appendChild($taxableAmountElement);

                // Calculate tax amount based on category
                $taxAmount  = 0.00;
                $percent    = 0.00;
                if ($taxCategoryID == 'S') {
                    $percent = 25.00;
                    $taxAmount = $taxableAmount * ($percent / 100);
                }
                $taxAmount  = number_format($taxAmount, 2, '.', '');
                $percent    = number_format($percent, 2, '.', '');

                $taxAmountElement = $dom->createElement('cbc:TaxAmount', $taxAmount);
                $taxAmountElement->setAttribute('currencyID', $currencyCode);
                $taxSubtotal->appendChild($taxAmountElement);

                $this->_log->log("Adding TaxSubtotal '$taxCategoryID' with amount: $taxAmount and percent: $percent%");

                $taxCategory = $dom->createElement('cac:TaxCategory');
                $taxSubtotal->appendChild($taxCategory);

                $taxCategoryIDElement = $dom->createElement('cbc:ID', $taxCategoryID);
                $taxCategory->appendChild($taxCategoryIDElement);

                $percentElement = $dom->createElement('cbc:Percent', $percent);
                $taxCategory->appendChild($percentElement);

                $taxScheme = $dom->createElement('cac:TaxScheme');
                $taxCategory->appendChild($taxScheme);

                $taxSchemeID = $dom->createElement('cbc:ID', 'VAT');
                $taxScheme->appendChild($taxSchemeID);
            }
//        }
    }

    /**
     * @param DOMDocument $dom
     * @return DOMDocument
     */
    private function removeNamespaceDeclarationAttributes(DOMDocument $dom): DOMDocument
    {
        // Get all elements in the document
        $elements = $dom->getElementsByTagName('*');

        // Iterate through each element
        foreach ($elements as $element) {
            // Check if the element has any namespace declaration attributes
            foreach ($element->attributes as $attribute) {
                if (strpos($attribute->name, 'xmlns:') === 0) {
                    // Remove the namespace declaration attribute
                    $element->removeAttributeNode($attribute);
                }
            }
        }

        // Save the modified XML content
        $tempDom = new DOMDocument;
        $tempDom->loadXML($dom->saveXML());
        return $tempDom;
    }



}