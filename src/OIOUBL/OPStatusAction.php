<?php

namespace Fakturaservice\Edelivery\OIOUBL;
/**
 * Status Clarification Action (OpenPEPPOL)
 *
 * Identifier:          OPStatusAction
 * Agency:              OpenPEPPOL
 * Version:             1.0
 * Usage:
 *                      | ubl:ApplicationResponse | cac:DocumentResponse | cac:Response | cac:Status | cbc:StatusReasonCode
 */
class OPStatusAction
{
    /**
     * No action required
     * No action required
     */
    const NOA = "NOA";

    /**
     * Provide information
     * Missing information requested without re-issuing invoice
     */
    const PIN = "PIN";

    /**
     * Issue new invoice
     * Request to re-issue a corrected invoice
     */
    const NIN = "NIN";

    /**
     * Credit fully
     * Request to fully cancel the referenced invoice with a credit note
     */
    const CNF = "CNF";

    /**
     * Credit partially
     * Request to issue partial credit note for corrections only
     */
    const CNP = "CNP";

    /**
     * Credit the amount
     * Request to repay the amount paid on the invoice
     */
    const CNA = "CNA";

    /**
     * Other
     * Requested action is not defined by code
     */
    const OTH = "OTH";

    static function msg(string $code): string
    {
        switch($code)
        {
            case self::NOA: return "No action required";
            case self::PIN: return "Provide information";
            case self::NIN: return "Issue new invoice";
            case self::CNF: return "Credit fully";
            case self::CNP: return "Credit partially";
            case self::CNA: return "Credit the amount";
            case self::OTH:
            default:         return "Other";
        }
    }
}