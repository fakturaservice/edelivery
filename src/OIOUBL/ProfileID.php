<?php

namespace Fakturaservice\Edelivery\OIOUBL;

abstract class ProfileID
{
    const nesubl_profile5_ver2_0            = "urn:www.nesubl.eu:profiles:profile5:ver2.0";
    const nesubl_profile8_ver2_0            = "urn:www.nesubl.eu:profiles:profile8:ver2.0";
    const procurement_BilSimR_1_0           = "Procurement-BilSimR-1.0";
    const procurement_BilSim_1_0            = "Procurement-BilSim-1.0";
    const reference_Utility_1_0             = "Reference-Utility-1.0";

    /** ubl:Invoice */
    const Peppol_BIS3_Billing               = "Peppol BIS3 Billing";
    const peppol_poacc_billing_01_1_0       = "urn:fdc:peppol.eu:2017:poacc:billing:01:1.0";

    /** ubl:ApplicationResponse */
    const peppol_poacc_bis_mlr_3            = "urn:fdc:peppol.eu:poacc:bis:mlr:3";

    const Peppol_BIS3_Invoice_Response      = "Peppol BIS3 Invoice Response";
    const peppol_poacc_invoice_response_3   = "urn:fdc:peppol.eu:poacc:bis:invoice_response:3";

    const peppol_eu_edec_bis_reporting_1    = "TESTurn:fdc:peppol.eu:edec:bis:reporting:1.0";

}