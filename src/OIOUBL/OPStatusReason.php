<?php

namespace Fakturaservice\Edelivery\OIOUBL;
/**
 * Status Clarification Reason (OpenPEPPOL)
 *
 * Identifier:          OPStatusReason
 * Agency:              OpenPEPPOL
 * Version:             1.0
 * Usage:
 *                      | ubl:ApplicationResponse | cac:DocumentResponse | cac:Response | cac:Status | cbc:StatusReasonCode
 */
class OPStatusReason
{
    /**
     * No Issue
     * Indicates that receiver of the documents sends the message just to update the status and there are no problems with document processing
     */
    const NON = "NON";

    /**
     * References incorrect
     * Indicates that the received document did not contain references as required by the receiver for correctly routing the document for approval or processing.
     */
    const REF = "REF";

    /**
     * Legal information incorrect
     * Information in the received document is not according to legal requirements.
     */
    const LEG = "LEG";

    /**
     * Receiver unknown
     * The party to which the document is addressed is not known.
     */
    const REC = "REC";

    /**
     * Item quality insufficient
     * Unacceptable or incorrect quality
     */
    const QUA = "QUA";

    /**
     * Delivery issues
     * Delivery proposed or provided is not acceptable.
     */
    const DEL = "DEL";

    /**
     * Prices incorrect
     * Prices not according to previous expectation.
     */
    const PRI = "PRI";

    /**
     * Quantity incorrect
     * Quantity not according to previous expectation.
     */
    const QTY = "QTY";

    /**
     * Items incorrect
     * Items not according to previous expectation.
     */
    const ITM = "ITM";

    /**
     * Payment terms incorrect
     * Payment terms not according to previous expectation.
     */
    const PAY = "PAY";

    /**
     * Not recognized
     * Commercial transaction not recognized.
     */
    const UNR = "UNR";

    /**
     * Finance incorrect
     * Finance terms not according to previous expectation.
     */
    const FIN = "FIN";

    /**
     * Partially Paid
     * Payment is partially but not fully paid.
     */
    const PPD = "PPD";

    /**
     * Other
     * Reason for status is not defined by code.
     */
    const OTH = "OTH";


    static function msg(string $code): string
    {
        switch($code)
        {
            case self::NON: return "No Issue";
            case self::REF: return "References incorrect";
            case self::LEG: return "Legal information incorrect";
            case self::REC: return "Receiver unknown";
            case self::QUA: return "Item quality insufficient";
            case self::DEL: return "Delivery issues";
            case self::PRI: return "Prices incorrect";
            case self::QTY: return "Quantity incorrect";
            case self::ITM: return "Items incorrect";
            case self::PAY: return "Payment terms incorrect";
            case self::UNR: return "Not recognized";
            case self::FIN: return "Finance incorrect";
            case self::PPD: return "Partially Paid";
            case self::OTH:
            default:        return "Other";
        }
    }
}