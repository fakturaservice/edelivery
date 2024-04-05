<?php
/**
 * Created by PhpStorm.
 * User: twl2
 * Date: 02-11-2020
 * Time: 11:37
 */

namespace Fakturaservice\Edelivery\OIOUBL;

use DOMElement;
use DOMException;

require_once __DIR__ . "/CatalogueType.php";
require_once __DIR__ . "/CustomizationID.php";
require_once __DIR__ . "/EndpointID.php";
require_once __DIR__ . "/EAS.php";
require_once __DIR__ . "/ICD.php";
require_once __DIR__ . "/NetworkType.php";
require_once __DIR__ . "/OIOUBL_base.php";
require_once __DIR__ . "/PartyIdentificationID.php";
require_once __DIR__ . "/PaymentChannelCode.php";
require_once __DIR__ . "/PaymentID.php";
require_once __DIR__ . "/PaymentMeansCode.php";
require_once __DIR__ . "/ProfileID.php";
require_once __DIR__ . "/ReminderTypeCode.php";
require_once __DIR__ . "/ResponseCode.php";
require_once __DIR__ . "/TaxSchemeID.php";
require_once __DIR__ . "/Urn.php";
require_once __DIR__ . "/UNCL1001.php";
require_once __DIR__ . "/UNCL4343.php";
require_once __DIR__ . "/OPStatusAction.php";
require_once __DIR__ . "/OPStatusReason.php";

/**
 * Class OIOUBL_lib
 */
class OIOUBL_lib extends OIOUBL_base
{
    protected function __construct(string $catalogueType, $UBLVersion, $inputXml=null)
    {
        parent::__construct($catalogueType, $UBLVersion, $inputXml);
    }
    
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Invoice(array $childElements): DOMElement
    {
        return $this->buildRoot($childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function CreditNote(array $childElements): DOMElement
    {
        return $this->buildRoot($childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Reminder(array $childElements): DOMElement
    {
        return $this->buildRoot($childElements);
    }
    protected function ApplicationResponse(array $childElements): DOMElement
    {
        return $this->buildRoot($childElements);
    }

    /**
     * @throws DOMException
     */
    protected function Response($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }

    /**
     * @throws DOMException
     */
    protected function ReferenceID($val=1, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    /**
     * @throws DOMException
     */
    protected function ResponseCode($val=ResponseCode::BusinessAccept, $attributes=["listAgencyID"=>"320", "listID"=>Urn::responsecode_1_1])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    /**
     * @throws DOMException
     */
    protected function Status($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }

    /**
     * @throws DOMException
     */
    protected function StatusReasonCode($val=OPStatusReason::OTH, $attributes=["listID"=>"OPStatusReason"])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    /**
     * @throws DOMException
     */
    protected function StatusReason($val, $attributes=["listID"=>"OPStatusReason"])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @throws DOMException
     */
    protected function DocumentTypeCode($val=CatalogueType::Invoice, $attributes=["listAgencyID"=>"320", "listID"=>Urn::responsedocumenttypecode_1_1])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    /**
     * @throws DOMException
     */
    protected function UBLVersionID($val=self::UBL_VERSION_2_1, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function CustomizationID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function ProfileID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    /**
     * @param string $val
     * @param array $attributes []
     * @return DOMElement|false
     * @throws DOMException
     */
    protected function ID(string $val, array $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function CopyIndicator($val="false", $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function UUID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function IssueDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function IssueTime($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function InvoiceTypeCode($val=UNCL1001::_380, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function CreditNoteTypeCode($val=UNCL1001::_381, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function Note($val=null, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function DueDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    

    protected function TaxPointDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function DocumentCurrencyCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function ReminderTypeCode($val=ReminderTypeCode::Advis, $attributes=["listAgencyID"=>"320", "listID"=>Urn::remindertypecode_1_1])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    protected function ReminderSequenceNumeric($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DiscrepancyResponse($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }    
    protected function TaxCurrencyCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PricingCurrencyCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PaymentCurrencyCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PaymentAlternativeCurrencyCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function AccountingCostCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function AccountingCost($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function LineCountNumeric($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function InvoicePeriod($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function OrderReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function SalesOrderID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CustomerReference($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Attachment($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function DocumentType($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function EmbeddedDocumentBinaryObject($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ExternalReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function URI($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function DocumentHash($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ExpiryDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ExpiryTime($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function XPath($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function BillingReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ReminderPeriod($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function StartDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function StartTime($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function EndDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function EndTime($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function InvoiceDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function SelfBilledInvoiceDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function CreditNoteDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function SelfBilledCreditNoteDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ReminderDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DespatchDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ReceiptDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function OriginatorDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ContractDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function AdditionalDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Signature($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function ValidationDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ValidationTime($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ValidatorID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CanonicalizationMethod($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function SignatureMethod($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function SignatoryParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DigitalSignatureAttachment($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function OriginalDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function AccountingSupplierParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function SenderParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function ReceiverParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function DocumentResponse($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function CustomerAssignedAccountID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function AdditionalAccountID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DespatchContact($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function Telephone($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function Telefax($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ElectronicMail($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function OtherCommunication($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function Value($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function AccountingContact($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function SellerContact($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Party($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PartyLegalEntity($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function AccountingCustomerParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function SupplierAssignedAccountID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DeliveryContact($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function BuyerContact($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }


    /**
     *
     * NOT YET IMPLEMENTED:
     *
     * "PayeeParty"                        => ["min" => 0, "max" => 0],
     * "BuyerCustomerParty"                => ["min" => 0, "max" => 1],
     * "SellerSupplierParty"               => ["min" => 0, "max" => 1],
     * "DeliveryTerms"                     => ["min" => 0, "max" => 1],
     * "PaymentMeans"                      => ["min" => 0, "max" => 0],
     * "PaymentTerms"                      => ["min" => 0, "max" => 0],
     * "PrepaidPayment"                    => ["min" => 0, "max" => 0],
     * "TaxExchangeRate"                   => ["min" => 0, "max" => 1],
     * "PricingExchangeRate"               => ["min" => 0, "max" => 1],
     * "PaymentExchangeRate"               => ["min" => 0, "max" => 1],
     * "PaymentAlternativeExchangeRate"    => ["min" => 0, "max" => 1],
     *
     */





    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function TaxTotal($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function TaxAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function RoundingAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function TaxEvidenceIndicator($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function TaxSubtotal($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function TaxableAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CalculationSequenceNumeric($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function TransactionCurrencyTaxAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function TaxCategory($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function Percent($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function BaseUnitMeasure($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PerUnitAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function TaxExemptionReasonCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function TaxExemptionReason($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function TaxScheme($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function TaxTypeCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CurrencyCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function JurisdictionRegionAddress($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function LegalMonetaryTotal($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function LineExtensionAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function TaxExclusiveAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function TaxInclusiveAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function AllowanceTotalAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ChargeTotalAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PrepaidAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PayableRoundingAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PayableAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function DebitLineAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CreditLineAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function InvoiceLine($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function CreditNoteLine($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ReminderLine($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    
    protected function InvoicedQuantity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CreditedQuantity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function FreeOfChargeIndicator($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function OrderLineReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function LineID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function SalesOrderLineID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function LineStatusCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DespatchLineReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ReceiptLineReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function BillingReferenceLine($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function Amount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function AllowanceCharge($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function ChargeIndicator($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function AllowanceChargeReasonCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function AllowanceChargeReason($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function MultiplierFactorNumeric($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PrepaidIndicator($val="false", $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function SequenceNumeric($val="1", $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function BaseAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PricingReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function OriginalItemLocationQuantity($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function LeadTimeMeasure($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function MinimumQuantity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function MaximumQuantity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function HazardousRiskIndicator($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function TradingRestrictions($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ApplicableTerritoryAddress($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Price($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function PriceAmount($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function BaseQuantity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PriceChangeReason($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PriceTypeCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PriceType($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function OrderableUnitFactorRate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ValidityPeriod($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PriceList($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DeliveryUnit($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function BatchQuantity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ConsumerUnitQuantity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ApplicableTaxCategory($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function AlternativeConditionPrice($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function OriginatorParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Delivery($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function Quantity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ActualDeliveryDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ActualDeliveryTime($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function TrackingID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DeliveryLocation($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function Description($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function Conditions($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Address($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PaymentMeans($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function PaymentMeansCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PaymentDueDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PaymentChannelCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function InstructionID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function InstructionNote($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PaymentID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function CreditAccount($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function AccountID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }


    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PaymentTerms($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function PaymentMeansID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PayerFinancialAccount($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PayeeFinancialAccount($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function FinancialAccount($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function AccountTypeCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PaymentNote($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function FinancialInstitutionBranch($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function FinancialInstitution($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function RequestedDeliveryPeriod($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DeliveryParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Despatch($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function RequestedDespatchDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function RequestedDespatchTime($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function EstimatedDespatchDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function EstimatedDespatchTime($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ActualDespatchDate($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ActualDespatchTime($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DespatchAddress($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function DespatchParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Contact($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Item($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function PackQuantity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PackSizeNumeric($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CatalogueIndicator($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function AdditionalInformation($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function Keyword($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function BrandName($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ModelName($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function BuyersItemIdentification($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function ExtendedID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function IssuerParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function SellersItemIdentification($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PhysicalAttribute($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function AttributeID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PositionCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function DescriptionCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function MeasurementDimension($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function Measure($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function MinimumMeasure($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function MaximumMeasure($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ManufacturersItemIdentification($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function StandardItemIdentification($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function CatalogueItemIdentification($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function AdditionalItemIdentification($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function CatalogueDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ItemSpecificationDocumentReference($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function OriginCountry($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function CommodityClassification($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function NatureCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CargoTypeCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CommodityCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function ItemClassificationCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function TransactionConditions($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function ActionCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function HazardousItem($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ClassifiedTaxCategory($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function AdditionalItemProperty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ManufacturerParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function InformationContentProviderParty($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function OriginAddress($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function ItemInstance($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function CompanyID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function RegistrationAddress($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param $val
     * @param $attributes[]
     * @return DOMElement
     */
    protected function EndpointID($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PartyIdentification($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PartyName($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Language($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function Name($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function LocaleCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function PostalAddress($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function AddressTypeCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function AddressFormatCode($val="StructuredDK", $attributes=["listAgencyID"=>"320", "listID"=>Urn::addressformatcode_1_1])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function Postbox($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function Floor($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function Room($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function StreetName($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function AdditionalStreetName($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function BuildingName($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function BuildingNumber($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function InhouseMail($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function Department($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function MarkAttention($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function MarkCare($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PlotIdentification($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CitySubdivisionName($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CityName($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function PostalZone($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CountrySubentity($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function CountrySubentityCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function Region($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    protected function District($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function AddressLine($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function Line($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     */
    protected function Country($childElements)
    {
        return $this->cac(__FUNCTION__, $childElements);
    }
    protected function IdentificationCode($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }

    /**
     * @param DOMElement[] $childElements
     * @return DOMElement
     * @throws DOMException
     */
    protected function PartyTaxScheme(array $childElements): DOMElement
    {
        return $this->cac(__FUNCTION__, $childElements);
    }

    /**
     * @throws DOMException
     */
    protected function RegistrationName($val, $attributes=[])
    {
        return $this->cbc(__FUNCTION__, $val, $attributes);
    }
    
}




