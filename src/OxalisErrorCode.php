<?php

namespace Fakturaservice\Edelivery;

abstract class OxalisErrorCode
{
    /**
     * Schematron error: %s
     */
    const E_APR21014 = "E-APR21014";
    /**
     * Schema error: %s
     */
    const E_APR21016 = "E-APR21016";
    /**
     * Missing or invalid COUNTRY_C1
     * business scope
     */
    const E_APR21019 = "E-APR21019";
    /**
     * No Nemhandel e-Delivery document
     * signature found
     */
    const E_APR21020 = "E-APR21020";
    /**
     * Invalid Nemhandel e-Delivery document
     * signature: %s
     */
    const E_APR21021 = "E-APR21021";
    /**
     * Found %d Nemhandel e-Delivery
     * document signatures. Only one document
     * signature is allowed.
     */
    const E_APR21022 = "E-APR21022";
    /**
     * Missing or invalid Nemhandel e-Delivery
     * specification version in Nemhandel e-
     * Delivery document signature scope.
     * Accepted values are %s
     */
    const E_APR21023 = "E-APR21023";
    /**
     * Receiver %s is not recognized by the
     * receiving endpoint. The document was
     * rejected by the receiving endpoint
     */
    const E_APR24123 = "E-APR24123";
    /**
     * Could not parse SchemeID and Identifier
     * from participant identifier: %s
     */
    const E_APR32001 = "E-APR32001";
    /**
     * An error occurred while creating Message
     * Level Response for message %s
     */
    const E_APR32002 = "E-APR32002";
    /**
     * An error occurred while converting
     * Message Level Response to OIOUBL
     * Application Response: %s
     */
    const E_APR32003 = "E-APR32003";
    /**
     * Schematron error: %s
     */
    const E_APS21014 = "E-APS21014";
    /**
     * Schema error: %s
     */
    const E_APS21016 = "E-APS21016";
    /**
     * Missing or invalid COUNTRY_C1
     * business scope
     */
    const E_APS21019 = "E-APS21019";
    /**
     * No Nemhandel e-Delivery document
     * signature found
     */
    const E_APS21020 = "E-APS21020";
    /**
     * Invalid Nemhandel e-Delivery document
     * signature: %s
     */
    const E_APS21021 = "E-APS21021";
    /**
     * Found %d Nemhandel e-Delivery
     * document signatures. Only one document
     * signature is allowed.
     */
    const E_APS21022 = "E-APS21022";
    /**
     * Missing or invalid Nemhandel e-Delivery
     * specification version in Nemhandel e-
     * Delivery document signature scope.
     * Accepted values are %s
     */
    const E_APS21023 = "E-APS21023";
    /**
     * Invalid XML
     */
    const E_APS24001 = "E-APS24001";
    /**
     * Could not parse Standard Business
     * Document header.
     */
    const E_APS24002 = "E-APS24002";
    /**
     * %s
     */
    const E_APS24003 = "E-APS24003";
    /**
     * An error occurred while connecting to
     * SMP.
     */
    const E_APS24004 = "E-APS24004";
    /**
     * No endpoint found during SMP lookup.
     */
    const E_APS24005 = "E-APS24005";
    /**
     * The receiver '%s' does not support
     * document type '%s' according to the SMP
     * lookup.
     */
    const E_APS24006 = "E-APS24006";
    /**
     * The sender '%s' does not support process
     * '%s' document type '%s' according to the
     * SMP lookup.
     */
    const E_APS24007 = "E-APS24007";
    /**
     * %s
     */
    const E_APS24999 = "E-APS24999";
    /**
     * %s
     */
    const E_REF10001 = "E-REF10001";
    /**
     * Standard Business Document is null or
     * empty
     */
    const E_REF23001 = "E-REF23001";
    /**
     * Schematron warning: %s
     */
    const W_APR21015 = "W-APR21015";
    /**
     * Schema warning: %s
     */
    const W_APR21017 = "W-APR21017";
    /**
     * Schematron warning: %s
     */
    const W_APS21015 = "W-APS21015";
    /**
     * Schema warning: %s
     */
    const W_APS21017 = "W-APS21017";
    /**
     * C3 receives a MLR/AR from C2 without
     * having sent anything.
     */
    const W_APS32301 = "W-APS32301";

}