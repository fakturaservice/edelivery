<?php
/**
 * Created by PhpStorm.
 * User: twl2
 * Date: 16-11-2020
 * Time: 11:09
 */

namespace Fakturaservice\Edelivery\OIOUBL;


use DOMDocument;
use DOMElement;
use DOMException;
use DOMXPath;
use XSLTProcessor;

/**
 * Class OIOUBL_base
 * @package OIOUBL
 */
abstract class OIOUBL_base
{

    const XML_VERSION               = "1.0";
    const XML_ENCODING              = "UTF-8";

    const XMLNS                     = "urn:oasis:names:specification:ubl:schema:xsd";

    const XMLNS_XSI                 = "http://www.w3.org/2001/XMLSchema-instance";

    const XMLNS_CAC                 = "urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2";
    const XMLNS_CBC                 = "urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2";
    const XMLNS_CEC                 = "urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2";
    const XMLNS_CCTS                = "urn:oasis:names:specification:ubl:schema:xsd:CoreComponentParameters-2";
    const XMLNS_SDT                 = "urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2";
    const XMLNS_UDT                 = "urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2";
    const XSI_SCHEMALOCATION        = "urn:oasis:names:specification:ubl:schema:xsd";

    const UBL_VERSION_2_0               = "2.0";
    const UBL_VERSION_2_1               = "2.1";
    const UBL_PEPPOL_VERSION_EN16931    = "EN16931";

    protected string $_resourcesPath;
    protected bool $_isOIOUBL;
    protected ?string $_UBLVersion;
    private DOMDocument $_dom;
    private DOMElement $_root;
    protected string $_catalogueType;

    /** @var mixed */
    private $_xslResult;
    protected string $cbcEmptyChar  = "-";
    protected ?string $rawInputXml;


    /**
     * @throws DOMException
     */
    protected function __construct(string $catalogueType, $UBLVersion, $resourcePath, $inputXml=null)
    {
        $this->_catalogueType   = $catalogueType;
        $this->_UBLVersion      = $UBLVersion;
        $this->_isOIOUBL        = (($UBLVersion == self::UBL_VERSION_2_0) || ($UBLVersion == self::UBL_VERSION_2_1));
        $this->_resourcesPath   = "$resourcePath/" . (($this->_isOIOUBL)?"OIOUBL":"PEPPOL");

        if(isset($inputXml))
        {
            $this->rawInputXml  = $inputXml;
            $this->_dom         = new DOMDocument();
            $this->_dom->loadXML($inputXml);
        }
        else
        {
            $this->_dom = new DOMDocument(self::XML_VERSION, self::XML_ENCODING);
            $this->Seed();
        }

    }

    private function getXsdFile($postfix=self::UBL_VERSION_2_1, $prefix="UBL"): string
    {
        if(strpos($postfix, self::UBL_PEPPOL_VERSION_EN16931) !== false)
        {
            return "$postfix-$prefix.sch";
        }
        switch($this->_catalogueType)
        {
            case CatalogueType::ApplicationResponse:                return "$prefix-ApplicationResponse-$postfix.xsd";
            case CatalogueType::AttachedDocument:                   return "$prefix-AttachedDocument-$postfix.xsd";
            case CatalogueType::BillOfLading:                       return "$prefix-BillOfLading-$postfix.xsd";
            case CatalogueType::Catalogue:                          return "$prefix-Catalogue-$postfix.xsd";
            case CatalogueType::CatalogueDeletion:                  return "$prefix-CatalogueDeletion-$postfix.xsd";
            case CatalogueType::CatalogueItemSpecificationUpdate:   return "$prefix-CatalogueItemSpecificationUpdate-$postfix.xsd";
            case CatalogueType::CataloguePricingUpdate:             return "$prefix-CataloguePricingUpdate-$postfix.xsd";
            case CatalogueType::CatalogueRequest:                   return "$prefix-CatalogueRequest-$postfix.xsd";
            case CatalogueType::CertificateOfOrigin:                return "$prefix-CertificateOfOrigin-$postfix.xsd";
            case CatalogueType::CreditNote:                         return "$prefix-CreditNote-$postfix.xsd";
            case CatalogueType::DebitNote:                          return "$prefix-DebitNote-$postfix.xsd";
            case CatalogueType::DespatchAdvice:                     return "$prefix-DespatchAdvice-$postfix.xsd";
            case CatalogueType::ForwardingInstructions:             return "$prefix-ForwardingInstructions-$postfix.xsd";
            case CatalogueType::FreightInvoice:                     return "$prefix-FreightInvoice-$postfix.xsd";
            case CatalogueType::Invoice:                            return "$prefix-Invoice-$postfix.xsd";
            case CatalogueType::Order:                              return "$prefix-Order-$postfix.xsd";
            case CatalogueType::OrderCancellation:                  return "$prefix-OrderCancellation-$postfix.xsd";
            case CatalogueType::OrderChange:                        return "$prefix-OrderChange-$postfix.xsd";
            case CatalogueType::OrderResponse:                      return "$prefix-OrderResponse-$postfix.xsd";
            case CatalogueType::OrderResponseSimple:                return "$prefix-OrderResponseSimple-$postfix.xsd";
            case CatalogueType::PackingList:                        return "$prefix-PackingList-$postfix.xsd";
            case CatalogueType::Quotation:                          return "$prefix-Quotation-$postfix.xsd";
            case CatalogueType::ReceiptAdvice:                      return "$prefix-ReceiptAdvice-$postfix.xsd";
            case CatalogueType::Reminder:                           return "$prefix-Reminder-$postfix.xsd";
            case CatalogueType::RemittanceAdvice:                   return "$prefix-RemittanceAdvice-$postfix.xsd";
            case CatalogueType::RequestForQuotation:                return "$prefix-RequestForQuotation-$postfix.xsd";
            case CatalogueType::SelfBilledCreditNote:               return "$prefix-SelfBilledCreditNote-$postfix.xsd";
            case CatalogueType::SelfBilledInvoice:                  return "$prefix-SelfBilledInvoice-$postfix.xsd";
            case CatalogueType::Statement:                          return "$prefix-Statement-$postfix.xsd";
            case CatalogueType::TransportationStatus:               return "$prefix-TransportationStatus-$postfix.xsd";
            case CatalogueType::Waybill:                            return "$prefix-Waybill-$postfix.xsd";
            default:                                                return "$prefix-$this->_catalogueType-$postfix.xsd";
        }
    }

    private function xsdError($error): string
    {
        $return = "</br>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "<b>Warning $error->code</b>: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "<b>Error $error->code</b>: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "<b>Fatal Error $error->code</b>: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .=    " in <b>$error->file</b>";
        }
        $return .= " on line <b>$error->line</b></br>\n";

        return $return;
    }

    private function xsdErrors(): string
    {
        $errors     = libxml_get_errors();
        $errorStr   = "\n<b>(" . ($this->_isOIOUBL?"OIOUBL":"PEPPOL") . ") XSD Error:</b></br>\n----------------</br>\n</br>\n";
        foreach ($errors as $error) {
            $errorStr .= $this->xsdError($error);
        }
        libxml_clear_errors();
        return $errorStr;
    }
    private function schValidation($schema): bool
    {
        // Create a new XSLTProcessor
        $xslt = new XSLTProcessor;

        // Load the Schematron schema
        $xslt->importStylesheet($schema);

        // Transform the document using the Schematron schema
        $result = $xslt->transformToDoc($this->_dom);

        // If the result document is empty, the document is valid
        return ($result && $result->documentElement->nodeName !== 'svrl:failed-assert');
    }
    protected function xsdValidation($getErrorMsg)
    {
        libxml_use_internal_errors(true);

        if($this->_resourcesPath === null)
            return "\n<b>(" . ($this->_isOIOUBL?"OIOUBL":"PEPPOL") . ") Resources Error:</b></br>\n----------------</br>\n</br>\n No " . ($this->_isOIOUBL?"OIOUBL":"PEPPOL") . " resource defined";

        if(!$this->_isOIOUBL)
        {
            $schCENFilePath     = "$this->_resourcesPath/xsd/" . $this->getXsdFile("CEN-$this->_UBLVersion");
            $schPEPPOLFilePath  = "$this->_resourcesPath/xsd/" . $this->getXsdFile("PEPPOL-$this->_UBLVersion");

            // Load the first Schematron schema (CEN-EN16931-UBL.sch)
            $cenSchema = new DOMDocument;
            $cenSchema->load($schCENFilePath);
            if(!$this->schValidation($cenSchema))
                return false;

            // Load the second Schematron schema (PEPPOL-EN16931-UBL.sch)
            $peppolSchema = new DOMDocument;
            $peppolSchema->load($schPEPPOLFilePath);
            if(!$this->schValidation($peppolSchema))
                return false;
            return true;
        }
        else
        {
            $xsdFilePath = "$this->_resourcesPath/xsd/" . $this->getXsdFile($this->_UBLVersion);
            if (!$this->_dom->schemaValidate($xsdFilePath)) {
                if ($getErrorMsg)
                    return $this->xsdErrors();
                else
                    return false;
            }
        }
        return true;
    }

    protected function saveUBL($xsdValidate=true, $xslValidate=false)
    {
        if($xsdValidate && !$this->xsdValidation(true))
            return $this->xsdErrors();
        if($xslValidate && !$this->xslValidation($xslValidate))
            return $this->_xslResult;
//        return $this->_dom->C14N();
        return $this->_dom->saveXML();
    }

    public function xslValidation($getErrorMsg=false)
    {
        if($this->_resourcesPath === null)
        {
            $this->_xslResult = "\n<b>(" . ($this->_isOIOUBL?"OIOUBL":"PEPPOL") . ") XSL Error:</b></br>\n----------------</br>\n</br>\n No XSL resource defined";
            return false;
        }
        $success    = true;
        if($this->_isOIOUBL)
            $xslFile    = "$this->_resourcesPath/xsl/OIOUBL_{$this->_catalogueType}_Schematron.xsl";
        else
            $xslFile    = "$this->_resourcesPath/xsl/stylesheet-ubl.xslt";
        if (in_array("Saxon/C", get_loaded_extensions()))
        {

            /*
             * How to install Saxon/C PHP: https://github.com/pointybeard/saxon
             * How to use Saxon/C PHP: https://stackoverflow.com/questions/56631544/how-to-make-a-xslt-tranformation-with-saxon-c-php-api
             *
             */

            // INITIALIZE PROCESSOR
            $saxonProc  = new \Saxon\SaxonProcessor();
            $xsltProc   = $saxonProc->newXsltProcessor();

            // LOAD XSLT SCRIPT
            $xsltProc->compileFromFile($xslFile);

            // LOAD SOURCE XML
            file_put_contents("/var/tmp/tmp.xml", $this->_dom->saveXML());
            $xsltProc->setSourceFromFile("/var/tmp/tmp.xml");


            // RUN TRANSFORMATION
            $this->_xslResult = $xsltProc->transformToString();

            // RELEASE RESOURCES
            $xsltProc->clearParameters();
            $xsltProc->clearProperties();

            unset($xsltProc);
            unset($saxonProc);
            unlink("/var/tmp/tmp.xml");
        }
        else
        {
            $xsl        = new XSLTProcessor();
            $xslDom     = new DOMDocument();

            $xslDom->load($xslFile);
            $xsl->importStylesheet($xslDom);
            $this->_xslResult = $xsl->transformToXml($this->_dom);

        }
        if(!$this->_xslResult || strstr($this->_xslResult,"<Error"))
        {
            $this->_xslResult = "\n<b>(" . ($this->_isOIOUBL?"OIOUBL":"PEPPOL") . ") XSL Error:</b></br>\n----------------</br>\n</br>\n" . $this->_xslResult;
            $success = false;
        }

        if($getErrorMsg && !$success)
        {
            return $this->_xslResult;
        }

        return $success;

    }

    /**
     * @throws DOMException
     */
    private function Seed()
    {
        $this->_dom->formatOutput = true;
        $this->_root = $this->_dom->createElementNS(self::XMLNS . ":$this->_catalogueType-2", $this->_catalogueType);
        $this->_dom->appendChild($this->_root);

        $this->_root->setAttributeNS('http://www.w3.org/2000/xmlns/', "xmlns:cac", self::XMLNS_CAC);
        $this->_root->setAttributeNS('http://www.w3.org/2000/xmlns/', "xmlns:cbc", self::XMLNS_CBC);

//        $this->_root->setAttributeNS('http://www.w3.org/2000/xmlns/', "xmlns:cbc", self::XMLNS_CEC);
//        $this->_root->setAttributeNS('http://www.w3.org/2000/xmlns/', "xmlns:ccts", self::XMLNS_CCTS);
//        $this->_root->setAttributeNS('http://www.w3.org/2000/xmlns/', "xmlns:sdt", self::XMLNS_SDT);
//        $this->_root->setAttributeNS('http://www.w3.org/2000/xmlns/', "xmlns:udt", self::XMLNS_UDT);
//
//        $schemaLocation_ns          = $this->_dom->createAttributeNS( self::XMLNS_XSI, 'xsi:schemaLocation' );
//        $schemaLocation_ns->value   = self::XSI_SCHEMALOCATION . ":{$this->_catalogueType}-2 " . $this->getXsdFile($UBLVersion);
//        $this->_root->appendChild($schemaLocation_ns);

    }

    /**
     * @param $childElements
     * @return DOMElement
     */
    protected function buildRoot($childElements): DOMElement
    {
        /** @var DOMElement $childElement */
        foreach ($childElements as $childElement)
        {
            if(isset($childElement))
                $this->_root->appendChild($childElement);
        }
        return $this->_root;
    }

    /**
     * @param $val
     * @return string
     */
    private function cbcTrim($val): string
    {
        return htmlspecialchars(trim($val), ENT_QUOTES | ENT_HTML401, self::XML_ENCODING);
    }

    /**
     * @throws DOMException
     */
    protected function cbc(string $name, $val=null, $attributes=[])
    {
        $element = $this->_dom->createElementNS(self::XMLNS_CBC, "cbc:$name", $this->cbcTrim($val));

        foreach ($attributes as $attr_name => $attr_val)
        {
            $attr        = $this->_dom->createAttribute($attr_name);
            $attr->value = $attr_val;
            $element->appendChild($attr);
        }

        return $element;
    }

    /**
     * @throws DOMException
     */
    protected function cac(string $name, $childElements)
    {
        $element = $this->_dom->createElementNS(self::XMLNS_CAC, "cac:$name");

        /** @var DOMElement $childElement */
        foreach ($childElements as $childElement)
        {
            if(isset($childElement))
                $element->appendChild($childElement);
        }
        return $element;
    }

    protected function generateRFC4122UUID(): string
    {
        // Generate a random 16-byte string
        $randomBytes = openssl_random_pseudo_bytes(16);

        // Set the version number to 4 (randomly generated UUID)
        $randomBytes[6] = chr(ord($randomBytes[6]) & 0x0f | 0x40);

        // Set the variant to the RFC 4122 variant (bits 7 and 8 of byte 8 should be '10')
        $randomBytes[8] = chr(ord($randomBytes[8]) & 0xbf | 0x80);

        // Convert the random bytes to a UUID string in the canonical format
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($randomBytes), 4));
    }

    protected function generateInstructionID($t, $paymentID): string
    {
        $t = preg_replace('/[^0-9]/', '', $t);

        switch($paymentID)
        {
            case PaymentID::_04:
            case PaymentID::_15:
            case PaymentID::_75:
                $padLen = 15; break;
            case PaymentID::_73:
            case PaymentID::_01:
                $padLen = 0; break;
            case PaymentID::_71:
                $padLen = 14; break;
            default:
                $padLen = 60; break;
        }
        if($padLen <= 0)
            return "";

        $t=str_pad($t, $padLen, 0, STR_PAD_LEFT);
        $v=1;
        $s=0;
        for ($i=0;$i<$padLen;$i++)
        {
            $r=$v*$t[$i];
            if ($r>9) $r=$r-9;
            $s+=$r;
            $v=$v==1?2:1;
        }
        return $t.fmod(10-fmod($s,10),10);
    }

    protected function getPaymentChannelCode($iban): string
    {
        $countryCode    = strtoupper(substr($iban, 0, 2));
        switch($countryCode)
        {
            case "AT":  //Østrig
            case "BE":  //Belgien
            case "BG":  //Bulgaria
            case "HR":  //Kroatien
            case "CY":  //Cypern
            case "CZ":  //Tjekkiet
            case "FO":  //Færøerne
            case "GL":  //Grønland
            case "DK":  //Danmark
            case "EE":  //Estland
            case "FI":  //Finland
            case "FR":  //Frankrig
            case "DE":  //Tyskland
            case "GI":  //Gibraltar
            case "GR":  //Grækenland
            case "HU":  //Ungarn
            case "IS":  //Island
            case "IE":  //Irland
            case "IT":  //Italien
            case "LV":  //Letland
            case "LI":  //Liechtenstein
            case "LT":  //Litauen
            case "LU":  //Luxembourg
            case "MT":  //Malta
            case "MC":  //Monaco
            case "NL":  //Holland
            case "NO":  //Norge
            case "PL":  //Polen
            case "PT":  //Portugal
            case "RO":  //Rumænien
            case "SM":  //San Marino
            case "SK":  //Slovakiet
            case "SI":  //Slovenien
            case "ES":  //Spanien
            case "SE":  //Sverige
            case "CH":  //Schweiz
            case "GB":  //Storbritannien
//            case "AL":  //Albanien
//            case "AD":  //Andorra
//            case "AZ":  //Aserbajdsjan
//            case "BH":  //Bahrain
//            case "BA":  //Bosnien og Hercegovina
                //...
                return PaymentChannelCode::IBAN;
            default:
                return PaymentChannelCode::ZZZ;
        }
    }

    protected function getNodeValue(string $name): array
    {
        $values         = [];
        $elements       = $this->_dom->getElementsByTagName($name);
        $nodeListLength = $elements->length; // this value will also change
        for ($i = 0; $i < $nodeListLength; $i++)
        {
            $node       = $elements->item($i);
            $values[$i] = $node->nodeValue;
        }

        return $values;
    }
    protected function getValueByXPath(string $xPathQuery, $idx=null)
    {
        $values         = [];
        $xpath          = new DOMXPath($this->_dom);
        $elements       = $xpath->query($xPathQuery);
        $nodeListLength = $elements->length; // this value will also change
        for ($i = 0; $i < $nodeListLength; $i++)
        {
            $node       = $elements->item($i);
            $values[$i] = $node->nodeValue;
        }
        return ((isset($idx) && isset($values[$idx]))?$values[$idx]:$values);
    }

}
