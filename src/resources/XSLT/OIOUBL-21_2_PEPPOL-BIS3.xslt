<?xml version="1.0" encoding="UTF-8"?>
<!--
******************************************************************************************************************

    Conversion from OIOUBL 2.0 / 2.1 to PEPPOL BIS3.

    Publisher:          Fakturaservice.dk

    Description:        General conversion of the OIOUBL 2.1 invoice and credit note syntax, to the PEPPOL BIS3 Billing syntax.
    Rights:             It can be used following the Common Creative License
                        Copyright (c) 2024. Fakturaservice A/S - All Rights Reserved
                        Unauthorized copying of this file, via any medium is strictly prohibited.
                        Proprietary and confidential
                        Written by Torben Wrang Laursen <twl@fakturaservice.dk>, June 2024

    Changed:20240620:   First initial template creation.
    Changed:20240711:   Now OIOUBL Invoice with negative InvoiceLine is converted to Allowance charge in BIS3.
                        ReverseCharge is also converted correct
    Changed:20240815:   Now OIOUBL CreditNote is also converted.
                        Testing for input document if it is OIOUBL Invoice or CreditNote, UBLVersionID = 2.0 / 2.1

******************************************************************************************************************
-->
<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:inv="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
                xmlns:cre="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2"
                xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                xmlns:sdt="urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2"
                xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                exclude-result-prefixes="inv cre sdt udt xsi">

    <xsl:output method="xml" encoding="UTF-8" indent="yes"/>
    <xsl:strip-space elements="*"/>



    <!-- Template to start processing the document from the root -->
    <xsl:template match="/">
        <xsl:choose>
            <!-- Check if the document is a valid OIOUBL Invoice with UBLVersionID 2.0 or 2.1 -->
            <xsl:when test="inv:Invoice
                          and (inv:Invoice/cbc:UBLVersionID = '2.0' or inv:Invoice/cbc:UBLVersionID = '2.1')
                          and contains(inv:Invoice/cbc:CustomizationID, 'OIOUBL')
                          and inv:Invoice/cbc:ProfileID">
                <xsl:apply-templates select="inv:Invoice"/>
            </xsl:when>

            <!-- Check if the document is a valid OIOUBL CreditNote with UBLVersionID 2.0 or 2.1 -->
            <xsl:when test="cre:CreditNote
                          and (cre:CreditNote/cbc:UBLVersionID = '2.0' or cre:CreditNote/cbc:UBLVersionID = '2.1')
                          and contains(cre:CreditNote/cbc:CustomizationID, 'OIOUBL')
                          and cre:CreditNote/cbc:ProfileID">
                <xsl:apply-templates select="cre:CreditNote"/>
            </xsl:when>

            <!-- Fallback if document is unsupported -->
            <xsl:otherwise>
                <Error>
                    <Errortext>Fatal error: Unsupported document type or missing required elements! This stylesheet only supports OIOUBL 2.0/2.1 Invoice or CreditNote.</Errortext>
                    <Input>
                        <xsl:value-of select="local-name(/*)"/>
                    </Input>
                </Error>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <!--Global Variables-->
    <xsl:variable name="DocumentCurrencyCode" select="/*/cbc:DocumentCurrencyCode"/>
    <xsl:variable name="TaxCurrencyCode" select="/*/cbc:TaxCurrencyCode"/>


    <xsl:variable name="SumLineExtensionAmountS" select="
    sum(
        /inv:Invoice/cac:InvoiceLine[
            cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID = 'StandardRated' and
            cbc:LineExtensionAmount/@currencyID = $DocumentCurrencyCode
        ]/cbc:LineExtensionAmount
    )
    +
    sum(
        /cre:CreditNote/cac:CreditNoteLine[
            cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID = 'StandardRated' and
            cbc:LineExtensionAmount/@currencyID = $DocumentCurrencyCode
        ]/cbc:LineExtensionAmount
    )
    +
    sum(
        /inv:Invoice/cac:AllowanceCharge[
            cac:TaxCategory/cbc:ID = 'StandardRated' and
            cbc:Amount/@currencyID = $DocumentCurrencyCode
        ]/cbc:Amount * (if (cbc:ChargeIndicator = 'true') then 1 else -1)
    )
    +
    sum(
        /cre:CreditNote/cac:AllowanceCharge[
            cac:TaxCategory/cbc:ID = 'StandardRated' and
            cbc:Amount/@currencyID = $DocumentCurrencyCode
        ]/cbc:Amount * (if (cbc:ChargeIndicator = 'true') then 1 else -1)
    )
    " />
    <xsl:variable name="StandardRatedCount" select="count(//cac:InvoiceLine/cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID[text()='StandardRated'] | //cac:CreditNoteLine/cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID[text()='StandardRated'])"/>

    <xsl:variable name="SumLineExtensionAmountZ" select="
    sum(
        /inv:Invoice/cac:InvoiceLine[
            cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID = 'ZeroRated' and
            cbc:LineExtensionAmount/@currencyID = $DocumentCurrencyCode
        ]/cbc:LineExtensionAmount
    )
    +
    sum(
        /cre:CreditNote/cac:CreditNoteLine[
            cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID = 'ZeroRated' and
            cbc:LineExtensionAmount/@currencyID = $DocumentCurrencyCode
        ]/cbc:LineExtensionAmount
    )
    +
    sum(
        /inv:Invoice/cac:AllowanceCharge[
            cac:TaxCategory/cbc:ID = 'ZeroRated' and
            cbc:Amount/@currencyID = $DocumentCurrencyCode
        ]/cbc:Amount * (if (cbc:ChargeIndicator = 'true') then 1 else -1)
    )
    +
    sum(
        /cre:CreditNote/cac:AllowanceCharge[
            cac:TaxCategory/cbc:ID = 'ZeroRated' and
            cbc:Amount/@currencyID = $DocumentCurrencyCode
        ]/cbc:Amount * (if (cbc:ChargeIndicator = 'true') then 1 else -1)
    )
    " />
    <xsl:variable name="ZeroRatedCount" select="count(//cac:InvoiceLine/cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID[text()='ZeroRated'] | //cac:CreditNoteLine/cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID[text()='ZeroRated'])"/>

    <xsl:variable name="SumLineExtensionAmountAE" select="
    sum(
        /inv:Invoice/cac:InvoiceLine[
            cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID = 'ReverseCharge' and
            cbc:LineExtensionAmount/@currencyID = $DocumentCurrencyCode
        ]/cbc:LineExtensionAmount
    )
    +
    sum(
        /cre:CreditNote/cac:CreditNoteLine[
            cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID = 'ReverseCharge' and
            cbc:LineExtensionAmount/@currencyID = $DocumentCurrencyCode
        ]/cbc:LineExtensionAmount
    )
    +
    sum(
        /inv:Invoice/cac:AllowanceCharge[
            cac:TaxCategory/cbc:ID = 'ReverseCharge' and
            cbc:Amount/@currencyID = $DocumentCurrencyCode
        ]/cbc:Amount * (if (cbc:ChargeIndicator = 'true') then 1 else -1)
    )
    +
    sum(
        /cre:CreditNote/cac:AllowanceCharge[
            cac:TaxCategory/cbc:ID = 'ReverseCharge' and
            cbc:Amount/@currencyID = $DocumentCurrencyCode
        ]/cbc:Amount * (if (cbc:ChargeIndicator = 'true') then 1 else -1)
    )
    " />
    <xsl:variable name="ReverseChargeCount" select="count(//cac:InvoiceLine/cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID[text()='ReverseCharge'] | //cac:CreditNoteLine/cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID[text()='ReverseCharge'])"/>

    <!-- Global variables for LineExtensionAmount, Charge, and Allowance -->
    <xsl:variable name="SumLineExtensionAmount" select="sum(/inv:Invoice/cac:InvoiceLine/cbc:LineExtensionAmount[. &gt; 0] | /cre:CreditNote/cac:CreditNoteLine/cbc:LineExtensionAmount[. &gt; 0])"/>
    <xsl:variable name="SumLineExtensionNegativeAmount" select="abs(sum(/inv:Invoice/cac:InvoiceLine/cbc:LineExtensionAmount[. &lt; 0] | /cre:CreditNote/cac:CreditNoteLine/cbc:LineExtensionAmount[. &lt; 0]))"/>
    <xsl:variable name="SumChargeAmount" select="sum(/inv:Invoice/cac:AllowanceCharge[cbc:ChargeIndicator='true']/cbc:Amount | /cre:CreditNote/cac:AllowanceCharge[cbc:ChargeIndicator='true']/cbc:Amount)"/>
    <xsl:variable name="SumAllowanceAmount" select="sum(/inv:Invoice/cac:AllowanceCharge[cbc:ChargeIndicator='false']/cbc:Amount | /cre:CreditNote/cac:AllowanceCharge[cbc:ChargeIndicator='false']/cbc:Amount) + $SumLineExtensionNegativeAmount"/>
    <xsl:variable name="TaxExclusiveAmount" select="$SumLineExtensionAmount + $SumChargeAmount - $SumAllowanceAmount"/>
    <xsl:variable name="SumTaxAmount" select="sum(/inv:Invoice/cac:TaxTotal/cbc:TaxAmount[@currencyID = $DocumentCurrencyCode] | /cre:CreditNote/cac:TaxTotal/cbc:TaxAmount[@currencyID = $DocumentCurrencyCode])"/>
    <xsl:variable name="TaxInclusiveAmount" select="$TaxExclusiveAmount + $SumTaxAmount"/>
    <xsl:variable name="SumPrepaidAmount" select="sum(/inv:Invoice/cac:LegalMonetaryTotal/cbc:PrepaidAmount | /cre:CreditNote/cac:LegalMonetaryTotal/cbc:PrepaidAmount)"/>
    <xsl:variable name="PayableAmount" select="$TaxInclusiveAmount - $SumPrepaidAmount"/>


    <!-- Template match for the root element -->
    <xsl:template match="/inv:Invoice">
        <!--Variables for Invoice header-->
        <xsl:variable name="CustomizationID" select="'urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0'"/>
        <xsl:variable name="ProfileID" select="'urn:fdc:peppol.eu:2017:poacc:billing:01:1.0'"/>
        <xsl:variable name="InvoiceNumber" select="cbc:ID"/>
        <xsl:variable name="IssueDate">
            <xsl:value-of select="cbc:IssueDate"/>
        </xsl:variable>
        <xsl:variable name="DueDate">
            <xsl:value-of select="cac:PaymentMeans[1]/cbc:PaymentDueDate"/>
        </xsl:variable>
        <xsl:variable name="InvoiceTypeCode">
            <xsl:choose>
                <xsl:when test="cbc:InvoiceTypeCode='325'">380</xsl:when>
                <xsl:when test="cbc:InvoiceTypeCode='380'">380</xsl:when>
                <xsl:when test="cbc:InvoiceTypeCode='393'">393</xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="380"/>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <xsl:variable name="TaxPointDate">
            <xsl:value-of select="cac:InvoiceLine/cbc:TaxPointDate"/>
        </xsl:variable>

        <!-- Start of BIS Invoice -->
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2">

            <cbc:CustomizationID>
                <xsl:value-of select="$CustomizationID"/>
            </cbc:CustomizationID>

            <cbc:ProfileID>
                <xsl:value-of select="$ProfileID"/>
            </cbc:ProfileID>

            <cbc:ID>
                <xsl:value-of select="$InvoiceNumber"/>
            </cbc:ID>

            <cbc:IssueDate>
                <xsl:value-of select="$IssueDate"/>
            </cbc:IssueDate>

            <xsl:if test="$DueDate">
                <cbc:DueDate>
                    <xsl:value-of select="$DueDate"/>
                </cbc:DueDate>
            </xsl:if>

            <cbc:InvoiceTypeCode>
                <xsl:value-of select="$InvoiceTypeCode"/>
            </cbc:InvoiceTypeCode>

            <xsl:if test="string(cbc:Note)">
                <cbc:Note>
                    <xsl:value-of select="cbc:Note"/>
                </cbc:Note>
            </xsl:if>

            <xsl:if test="string(cbc:TaxPointDate)">
                <cbc:TaxPointDate>
                    <xsl:value-of select="cbc:TaxPointDate"/>
                </cbc:TaxPointDate>
            </xsl:if>

            <cbc:DocumentCurrencyCode>
                <xsl:value-of select="cbc:DocumentCurrencyCode"/>
            </cbc:DocumentCurrencyCode>

            <xsl:if test="string(cbc:TaxCurrencyCode) != '' and string(cbc:TaxCurrencyCode) != string(cbc:DocumentCurrencyCode)">
                <cbc:TaxCurrencyCode>
                    <xsl:value-of select="cbc:TaxCurrencyCode"/>
                </cbc:TaxCurrencyCode>
            </xsl:if>

            <xsl:if test="string(cbc:AccountingCost)">
                <cbc:AccountingCost>
                    <xsl:value-of select="cbc:AccountingCost"/>
                </cbc:AccountingCost>
            </xsl:if>

            <!--Inserting InvoicePeriod if present-->
            <xsl:if test="cac:InvoicePeriod/cbc:StartDate or cac:InvoicePeriod/cbc:EndDate">
                <xsl:apply-templates select="cac:InvoicePeriod"/>
            </xsl:if>
            <!-- OrderReference -->
            <xsl:choose>
                <xsl:when test="not(cac:OrderReference) and not(cbc:BuyerReference)">
                    <cbc:BuyerReference>n/a</cbc:BuyerReference>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:apply-templates select="cac:OrderReference"/>
                </xsl:otherwise>
            </xsl:choose>
            <!-- Billing Reference -->
            <xsl:apply-templates select="cac:BillingReference"/>
            <!-- Despatch document Reference -->
            <xsl:apply-templates select="cac:DespatchDocumentReference"/>
            <!-- Receipt document Reference -->
            <xsl:apply-templates select="cac:ReceiptDocumentReference"/>
            <!-- Originator document Reference -->
            <xsl:apply-templates select="cac:OriginatorDocumentReference"/>
            <!-- Contract document Reference -->
            <xsl:apply-templates select="cac:ContractDocumentReference"/>
            <!-- Additional document Reference -->
            <xsl:apply-templates select="cac:AdditionalDocumentReference"/>
            <!-- AccountingSupplierParty -->
            <xsl:apply-templates select="cac:AccountingSupplierParty"/>
            <!-- AccountingCustomerParty -->
            <xsl:apply-templates select="cac:AccountingCustomerParty"/>
            <!-- PayeeParty -->
            <xsl:if test="
                (cac:AccountingSupplierParty/cac:Party/cac:PartyIdentification/cbc:ID != cac:PayeeParty/cac:PartyIdentification/cbc:ID)
                and
                (cac:AccountingSupplierParty/cac:Party/cac:PartyName/cbc:Name != cac:PayeeParty/cac:PartyName/cbc:Name)">
                <xsl:apply-templates select="cac:PayeeParty"/>
            </xsl:if>

            <!-- Delivery Header Party -->
            <xsl:apply-templates select="cac:Delivery"/>
            <!-- Payment Means Party -->
            <xsl:apply-templates select="cac:PaymentMeans"/>
            <!-- PaymentTerms -->
            <xsl:apply-templates select="cac:PaymentTerms"/>
            <!-- AllowanceCharge -->
            <xsl:apply-templates select="/inv:Invoice/cac:AllowanceCharge"/>

            <!-- Add AllowanceCharge if there are any discounts -->
            <xsl:if test="$SumAllowanceAmount > 0">

                <!--                <xsl:for-each select="/inv:Invoice/cac:InvoiceLine[cbc:LineExtensionAmount &lt; 0]">-->
                <!--                    <cac:AllowanceCharge>-->
                <!--                        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>-->
                <!--                        <cbc:AllowanceChargeReason>-->
                <!--                            <xsl:value-of select="cac:Item/cbc:Name"/>-->
                <!--                        </cbc:AllowanceChargeReason>-->
                <!--                        <cbc:Amount currencyID="{$DocumentCurrencyCode}">-->
                <!--                            <xsl:value-of select="abs(cbc:LineExtensionAmount)"/>-->
                <!--                        </cbc:Amount>-->
                <!--                        <cac:TaxCategory>-->
                <!--                            <cbc:ID>-->
                <!--                                <xsl:call-template name="mapTaxCode">-->
                <!--                                    <xsl:with-param name="taxString" select="cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID"/>-->
                <!--                                </xsl:call-template>-->
                <!--                            </cbc:ID>-->
                <!--                            <cbc:Percent>-->
                <!--                                <xsl:value-of select="cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:Percent"/>-->
                <!--                            </cbc:Percent>-->
                <!--                            <cac:TaxScheme>-->
                <!--                                <cbc:ID>VAT</cbc:ID>-->
                <!--                            </cac:TaxScheme>-->
                <!--                        </cac:TaxCategory>-->
                <!--                    </cac:AllowanceCharge>-->
                <!--                </xsl:for-each>-->


                <xsl:for-each select="/inv:Invoice/cac:InvoiceLine/cbc:LineExtensionAmount[. &lt; 0]">
                    <cac:AllowanceCharge>
                        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                        <cbc:AllowanceChargeReason>
                            <xsl:value-of select="../cac:Item/cbc:Name"/>
                        </cbc:AllowanceChargeReason>
                        <cbc:Amount currencyID="{$DocumentCurrencyCode}">
                            <xsl:value-of select="format-number(abs(../cbc:LineExtensionAmount), '0.00')"/>
                        </cbc:Amount>
                        <cac:TaxCategory>
                            <cbc:ID>
                                <xsl:call-template name="mapTaxCode">
                                    <xsl:with-param name="taxString" select="../cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID"/>
                                </xsl:call-template>
                            </cbc:ID>
                            <cbc:Percent>
                                <xsl:value-of select="format-number(../cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:Percent, '0.00')"/>
                            </cbc:Percent>
                            <cac:TaxScheme>
                                <cbc:ID>VAT</cbc:ID>
                            </cac:TaxScheme>
                        </cac:TaxCategory>
                    </cac:AllowanceCharge>
                </xsl:for-each>


            </xsl:if>

            <xsl:if test="cbc:DocumentCurrencyCode">
                <xsl:apply-templates select="cac:TaxTotal[1]">
                    <xsl:with-param name="CurrencyCode" select="cbc:DocumentCurrencyCode"/>
                </xsl:apply-templates>
            </xsl:if>

            <!-- If TaxCurrencyCode is present and differs from DocumentCurrencyCode -->
            <xsl:if test="string(cbc:TaxCurrencyCode) != string(cbc:DocumentCurrencyCode)">
                <xsl:apply-templates select="cac:TaxTotal[2]">
                    <xsl:with-param name="CurrencyCode" select="cbc:TaxCurrencyCode"/>
                </xsl:apply-templates>
            </xsl:if>

            <!--            <xsl:apply-templates select="cac:TaxTotal">-->
            <!--                <xsl:with-param name="CurrencyCode" select="cbc:TaxCurrencyCode"/>-->
            <!--            </xsl:apply-templates>-->

            <xsl:apply-templates select="cac:LegalMonetaryTotal"/>

            <!--InvoiceLines-->
            <!--            <xsl:apply-templates select="cac:InvoiceLine"/>-->
            <xsl:apply-templates select="cac:InvoiceLine[cac:Price/cbc:PriceAmount &gt;= 0]"/>

        </Invoice>
    </xsl:template>

    <xsl:template match="/cre:CreditNote">
        <!--Variables for Invoice header-->
        <xsl:variable name="CustomizationID" select="'urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0'"/>
        <xsl:variable name="ProfileID" select="'urn:fdc:peppol.eu:2017:poacc:billing:01:1.0'"/>
        <xsl:variable name="CreditNoteNumber" select="cbc:ID"/>
        <xsl:variable name="IssueDate">
            <xsl:value-of select="cbc:IssueDate"/>
        </xsl:variable>

        <!-- Start of BIS CreditNote -->
        <CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2">

            <cbc:CustomizationID>
                <xsl:value-of select="$CustomizationID"/>
            </cbc:CustomizationID>

            <cbc:ProfileID>
                <xsl:value-of select="$ProfileID"/>
            </cbc:ProfileID>

            <cbc:ID>
                <xsl:value-of select="$CreditNoteNumber"/>
            </cbc:ID>

            <cbc:IssueDate>
                <xsl:value-of select="$IssueDate"/>
            </cbc:IssueDate>

            <xsl:if test="string(cbc:TaxPointDate)">
                <cbc:TaxPointDate>
                    <xsl:value-of select="cbc:TaxPointDate"/>
                </cbc:TaxPointDate>
            </xsl:if>

            <cbc:CreditNoteTypeCode>
                <xsl:value-of select="'381'"/>
            </cbc:CreditNoteTypeCode>

            <xsl:if test="string(cbc:Note)">
                <cbc:Note>
                    <xsl:value-of select="cbc:Note"/>
                </cbc:Note>
            </xsl:if>

            <cbc:DocumentCurrencyCode>
                <xsl:value-of select="cbc:DocumentCurrencyCode"/>
            </cbc:DocumentCurrencyCode>

            <xsl:if test="string(cbc:TaxCurrencyCode) != '' and string(cbc:TaxCurrencyCode) != string(cbc:DocumentCurrencyCode)">
                <cbc:TaxCurrencyCode>
                    <xsl:value-of select="cbc:TaxCurrencyCode"/>
                </cbc:TaxCurrencyCode>
            </xsl:if>

            <xsl:if test="string(cbc:AccountingCost)">
                <cbc:AccountingCost>
                    <xsl:value-of select="cbc:AccountingCost"/>
                </cbc:AccountingCost>
            </xsl:if>

            <!-- InvoicePeriod -->
            <xsl:if test="cac:InvoicePeriod/cbc:StartDate or cac:InvoicePeriod/cbc:EndDate">
                <xsl:apply-templates select="cac:InvoicePeriod"/>
            </xsl:if>

            <!-- BuyerReference / OrderReference -->
            <xsl:choose>
                <xsl:when test="not(cac:OrderReference) and not(cbc:BuyerReference)">
                    <cbc:BuyerReference>n/a</cbc:BuyerReference>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:apply-templates select="cac:OrderReference"/>
                </xsl:otherwise>
            </xsl:choose>

            <!-- Other references and parties, similarly applied as in Invoice -->
            <!--            <xsl:apply-templates select="cac:OrderReference"/>-->
            <xsl:apply-templates select="cac:BillingReference"/>
            <xsl:apply-templates select="cac:DespatchDocumentReference"/>
            <xsl:apply-templates select="cac:ReceiptDocumentReference"/>
            <xsl:apply-templates select="cac:ContractDocumentReference"/>

            <xsl:apply-templates select="cac:AdditionalDocumentReference"/>
            <xsl:apply-templates select="cac:OriginatorDocumentReference"/>

            <xsl:apply-templates select="cac:AccountingSupplierParty"/>
            <xsl:apply-templates select="cac:AccountingCustomerParty"/>
            <xsl:if test="
                (cac:AccountingSupplierParty/cac:Party/cac:PartyIdentification/cbc:ID[@schemeID != 'GLN'] != cac:PayeeParty/cac:PartyIdentification/cbc:ID)
                and
                (cac:AccountingSupplierParty/cac:Party/cac:PartyName/cbc:Name != cac:PayeeParty/cac:PartyName/cbc:Name)">
                <xsl:apply-templates select="cac:PayeeParty"/>
            </xsl:if>
            <!--            cac:TaxRepresentativeParty-->
            <xsl:apply-templates select="cac:Delivery"/>
            <xsl:apply-templates select="cac:PaymentMeans"/>
            <xsl:apply-templates select="cac:PaymentTerms"/>
            <xsl:apply-templates select="/*/cac:AllowanceCharge"/>

            <!-- Add AllowanceCharge if there are any discounts -->
            <xsl:if test="$SumAllowanceAmount > 0">

                <xsl:for-each select="/cre:CreditNote/cac:CreditNoteLine/cbc:LineExtensionAmount[. &lt; 0]">
                    <cac:AllowanceCharge>
                        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                        <cbc:AllowanceChargeReason>
                            <xsl:value-of select="../cac:Item/cbc:Name"/>
                        </cbc:AllowanceChargeReason>
                        <cbc:Amount currencyID="{$DocumentCurrencyCode}">
                            <xsl:value-of select="format-number(abs(../cbc:LineExtensionAmount), '0.00')"/>
                        </cbc:Amount>
                        <cac:TaxCategory>
                            <cbc:ID>
                                <xsl:call-template name="mapTaxCode">
                                    <xsl:with-param name="taxString" select="../cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID"/>
                                </xsl:call-template>
                            </cbc:ID>
                            <cbc:Percent>
                                <xsl:value-of select="format-number(../cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:Percent, '0.00')"/>
                            </cbc:Percent>
                            <cac:TaxScheme>
                                <cbc:ID>VAT</cbc:ID>
                            </cac:TaxScheme>
                        </cac:TaxCategory>
                    </cac:AllowanceCharge>
                </xsl:for-each>


            </xsl:if>

            <xsl:apply-templates select="cac:TaxTotal"/>
            <xsl:apply-templates select="cac:LegalMonetaryTotal"/>
            <xsl:apply-templates select="cac:CreditNoteLine[cac:Price/cbc:PriceAmount &gt;= 0]"/>

        </CreditNote>
    </xsl:template>







    <!-- ............................................................ -->
    <!--           Templates for Invoice start						  -->
    <!-- ............................................................ -->
    <!--Invoice Period-->
    <xsl:template match="cac:InvoicePeriod">
        <cac:InvoicePeriod>
            <xsl:if test="cbc:StartDate">
                <cbc:StartDate>
                    <xsl:value-of select="cbc:StartDate"/>
                </cbc:StartDate>
            </xsl:if>
            <xsl:if test="cbc:EndDate">
                <cbc:EndDate>
                    <xsl:value-of select="cbc:EndDate"/>
                </cbc:EndDate>
            </xsl:if>
        </cac:InvoicePeriod>
    </xsl:template>
    <!--OrderReference-->
    <xsl:template match="cac:OrderReference">
        <cac:OrderReference>
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>
            <xsl:if test="string(cbc:SalesOrderID)">
                <cbc:SalesOrderID>
                    <xsl:value-of select="cbc:SalesOrderID"/>
                </cbc:SalesOrderID>
            </xsl:if>
        </cac:OrderReference>
    </xsl:template>
    <!--Billing Reference-->
    <xsl:template match="cac:BillingReference">
        <cac:BillingReference>
            <cac:InvoiceDocumentReference>
                <cbc:ID>
                    <xsl:value-of select="cac:InvoiceDocumentReference/cbc:ID"/>
                </cbc:ID>
                <xsl:if test="cbc:IssueDate">
                    <cbc:IssueDate>
                        <xsl:value-of select="cac:InvoiceDocumentReference/cbc:IssueDate"/>
                    </cbc:IssueDate>
                </xsl:if>
            </cac:InvoiceDocumentReference>
        </cac:BillingReference>
    </xsl:template>
    <!--Despatch document Reference-->
    <xsl:template match="cac:DespatchDocumentReference">
        <cac:DespatchDocumentReference>
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>
        </cac:DespatchDocumentReference>
    </xsl:template>
    <!--Receipt document Reference-->
    <xsl:template match="cac:ReceiptDocumentReference">
        <cac:ReceiptDocumentReference>
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>
        </cac:ReceiptDocumentReference>
    </xsl:template>
    <!--Originator document Reference-->
    <xsl:template match="cac:OriginatorDocumentReference">
        <cac:OriginatorDocumentReference>
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>
        </cac:OriginatorDocumentReference>
    </xsl:template>
    <!--Contract document Reference-->
    <xsl:template match="cac:ContractDocumentReference">
        <cac:ContractDocumentReference>
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>
        </cac:ContractDocumentReference>
    </xsl:template>
    <!--Additional document Reference-->
    <xsl:template match="cac:AdditionalDocumentReference">
        <xsl:variable name="MimeCode" select="cac:Attachment/cbc:EmbeddedDocumentBinaryObject/@mimeCode"/>
        <xsl:variable name="fileName" select="cac:Attachment/cbc:EmbeddedDocumentBinaryObject/@filename"/>
        <xsl:variable name="Type" select="cbc:DocumentType"/>
        <xsl:variable name="TypeCode" select="cbc:DocumentTypeCode"/>
        <cac:AdditionalDocumentReference>
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>
            <xsl:if test="$TypeCode">
                <cbc:DocumentTypeCode>
                    <xsl:value-of select="$TypeCode"/>
                </cbc:DocumentTypeCode>
            </xsl:if>
            <xsl:if test="$Type">
                <cbc:DocumentDescription>
                    <xsl:value-of select="$Type"/>
                </cbc:DocumentDescription>
            </xsl:if>
            <xsl:if test="cac:Attachment/cbc:EmbeddedDocumentBinaryObject">
                <cac:Attachment>
                    <cbc:EmbeddedDocumentBinaryObject mimeCode="{$MimeCode}" filename="{$fileName}">
                        <xsl:value-of select="cac:Attachment/cbc:EmbeddedDocumentBinaryObject"/>
                    </cbc:EmbeddedDocumentBinaryObject>
                    <xsl:if test="string(cac:ExternalReference/cbc:URI)">
                        <cac:ExternalReference>
                            <cbc:URI>
                                <xsl:value-of select="cac:ExternalReference/cbc:URI"/>
                            </cbc:URI>
                        </cac:ExternalReference>
                    </xsl:if>
                </cac:Attachment>
            </xsl:if>
        </cac:AdditionalDocumentReference>
    </xsl:template>

    <!--Accounting Supplier Party main template -->
    <xsl:template match="cac:AccountingSupplierParty">
        <!--Variables-->
        <xsl:variable name="SchemeIDEndpointID" select="cac:Party/cbc:EndpointID/@schemeID"/>
        <xsl:variable name="SchemeIDEndpointIDValue" select="cac:Party/cbc:EndpointID"/>

        <xsl:variable name="SchemeIDPartyIdentificationID" select="cac:Party/cac:PartyIdentification/cbc:ID[@schemeID != 'GLN'][1]/@schemeID"/>
        <xsl:variable name="SchemeIDPartyIdentificationIDValue" select="cac:Party/cac:PartyIdentification/cbc:ID[@schemeID != 'GLN'][1]"/>

        <xsl:variable name="SchemeIDCompanyID" select="cac:Party/cac:PartyLegalEntity/cbc:CompanyID/@schemeID"/>
        <xsl:variable name="SchemeIDCompanyIDValue" select="cac:Party/cac:PartyLegalEntity/cbc:CompanyID"/>

        <!--Variables for the supplier class-->
        <cac:AccountingSupplierParty>
            <cac:Party>
                <!-- if EndpointID is found on seller endPointid is mapped-->
                <xsl:if test="string($SchemeIDEndpointIDValue)">

                    <!-- Call the mapping template -->
                    <xsl:call-template name="SchemeIDmapping">
                        <xsl:with-param name="ElementName" select="'cbc:EndpointID'"/>
                        <xsl:with-param name="SchemeID" select="$SchemeIDEndpointID"/>
                        <xsl:with-param name="SchemeIDValue" select="$SchemeIDEndpointIDValue"/>
                    </xsl:call-template>

                </xsl:if>
                <!--If GlobalID for the Seller is found the PartyIdentification class is created on behalf of that otherwise on the ram:ID of the seller otherwise nothing-->
                <xsl:choose>
                    <xsl:when test="string($SchemeIDPartyIdentificationIDValue)">

                        <cac:PartyIdentification>
                            <xsl:call-template name="SchemeIDmapping">
                                <xsl:with-param name="ElementName" select="'cbc:ID'"/>
                                <xsl:with-param name="SchemeID" select="$SchemeIDPartyIdentificationID"/>
                                <xsl:with-param name="SchemeIDValue" select="$SchemeIDPartyIdentificationIDValue"/>
                            </xsl:call-template>
                        </cac:PartyIdentification>

                    </xsl:when>
                    <xsl:when test="string(cac:PartyIdentification/cbc:ID)">
                        <cac:PartyIdentification>
                            <cbc:ID>
                                <xsl:value-of select="cac:PartyIdentification/cbc:ID"/>
                            </cbc:ID>
                        </cac:PartyIdentification>
                    </xsl:when>
                </xsl:choose>

                <!--If CII seller PartyName is present it is mapped to BIS PArtyname-->
                <xsl:if test="string(cac:Party/cac:PartyName/cbc:Name)">
                    <cac:PartyName>
                        <cbc:Name>
                            <xsl:value-of select="cac:Party/cac:PartyName/cbc:Name"/>
                        </cbc:Name>
                    </cac:PartyName>
                </xsl:if>
                <!--Seller postal address is always created because country code is mandatory-->
                <cac:PostalAddress>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:StreetName)">
                        <cbc:StreetName>
                            <xsl:choose>
                                <xsl:when test="cac:Party/cac:PostalAddress/cbc:BuildingNumber">
                                    <xsl:value-of select="concat(cac:Party/cac:PostalAddress/cbc:StreetName, ' ', cac:Party/cac:PostalAddress/cbc:BuildingNumber)"/>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:StreetName"/>
                                </xsl:otherwise>
                            </xsl:choose>
                        </cbc:StreetName>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:AdditionalStreetName)">
                        <cbc:AdditionalStreetName>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:AdditionalStreetName"/>
                        </cbc:AdditionalStreetName>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:CityName)">
                        <cbc:CityName>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:CityName"/>
                        </cbc:CityName>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:PostalZone)">
                        <cbc:PostalZone>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:PostalZone"/>
                        </cbc:PostalZone>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:CountrySubentity)">
                        <cbc:CountrySubentity>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:CountrySubentity"/>
                        </cbc:CountrySubentity>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cac:AddressLine/cbc:Line)">
                        <cac:AddressLine>
                            <cbc:Line>
                                <xsl:value-of select="cac:Party/cac:PostalAddress/cac:AddressLine/cbc:Line"/>
                            </cbc:Line>
                        </cac:AddressLine>
                    </xsl:if>
                    <!--Mandatory is address class-->
                    <cac:Country>
                        <cbc:IdentificationCode>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cac:Country/cbc:IdentificationCode"/>
                        </cbc:IdentificationCode>
                    </cac:Country>
                </cac:PostalAddress>

                <!-- Check if cac:PartyTaxScheme is missing and if there's an InvoiceLine with cac:ClassifiedTaxCategory/cbc:ID = 'S' -->
                <xsl:if test="not(cac:Party/cac:PartyTaxScheme)">
                    <cac:PartyTaxScheme>
                        <!-- Set the value of cbc:CompanyID from PartyLegalEntity/CompanyID -->
                        <cbc:CompanyID>
                            <xsl:value-of select="cac:Party/cac:PartyLegalEntity/cbc:CompanyID"/>
                        </cbc:CompanyID>
                        <cac:TaxScheme>
                            <cbc:ID>VAT</cbc:ID>
                        </cac:TaxScheme>
                    </cac:PartyTaxScheme>
                </xsl:if>

                <xsl:if test="cac:Party/cac:PartyTaxScheme/cbc:CompanyID[@schemeID ='DK:SE']">
                    <cac:PartyTaxScheme>
                        <cbc:CompanyID>
                            <xsl:value-of select="cac:Party/cac:PartyTaxScheme/cbc:CompanyID[@schemeID ='DK:SE']"/>
                        </cbc:CompanyID>
                        <cac:TaxScheme>
                            <cbc:ID>VAT</cbc:ID>
                        </cac:TaxScheme>
                    </cac:PartyTaxScheme>
                </xsl:if>
                <xsl:if test="cac:Party/cac:PartyTaxScheme/cbc:CompanyID[@schemeID !='DK:SE']">
                    <cac:PartyTaxScheme>
                        <cbc:CompanyID>
                            <xsl:value-of select="cac:Party/cac:PartyTaxScheme/cbc:CompanyID[@schemeID !='DK:SE']"/>
                        </cbc:CompanyID>
                        <cac:TaxScheme>
                            <cbc:ID>TAX</cbc:ID>
                        </cac:TaxScheme>
                    </cac:PartyTaxScheme>
                </xsl:if>
                <!-- PartyLegalEntity will always be present for the supplier because the company registration name is mandatory-->
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName>
                        <xsl:choose>
                            <xsl:when test="string(cac:Party/cac:PartyLegalEntity/cbc:RegistrationName) != ''">
                                <xsl:value-of select="cac:Party/cac:PartyLegalEntity/cbc:RegistrationName"/>
                            </xsl:when>
                            <xsl:when test="string(cac:Party/cac:PartyName/cbc:Name) != ''">
                                <xsl:value-of select="cac:Party/cac:PartyName/cbc:Name"/>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:text>n/a</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>
                    </cbc:RegistrationName>
                    <!-- Call the mapping template -->
                    <xsl:call-template name="SchemeIDmapping">
                        <xsl:with-param name="ElementName" select="'cbc:CompanyID'"/>
                        <xsl:with-param name="SchemeID" select="$SchemeIDCompanyID"/>
                        <xsl:with-param name="SchemeIDValue" select="$SchemeIDCompanyIDValue"/>
                    </xsl:call-template>
                </cac:PartyLegalEntity>
                <!--Contact class will be created if the defined trad contact is present in the CII Invoice-->
                <xsl:if test="normalize-space(cac:Party/cac:Contact/cbc:Name) != '' or normalize-space(cac:Party/cac:Contact/cbc:Telephone) != '' or normalize-space(cac:Party/cac:Contact/cbc:ElectronicMail) != ''">
                    <cac:Contact>
                        <xsl:if test="normalize-space(cac:Party/cac:Contact/cbc:Name) != ''">
                            <cbc:Name>
                                <xsl:value-of select="cac:Party/cac:Contact/cbc:Name"/>
                            </cbc:Name>
                        </xsl:if>
                        <xsl:if test="normalize-space(cac:Party/cac:Contact/cbc:Telephone) != ''">
                            <cbc:Telephone>
                                <xsl:value-of select="cac:Party/cac:Contact/cbc:Telephone"/>
                            </cbc:Telephone>
                        </xsl:if>
                        <xsl:if test="normalize-space(cac:Party/cac:Contact/cbc:ElectronicMail) != ''">
                            <cbc:ElectronicMail>
                                <xsl:value-of select="cac:Party/cac:Contact/cbc:ElectronicMail"/>
                            </cbc:ElectronicMail>
                        </xsl:if>
                    </cac:Contact>
                </xsl:if>
            </cac:Party>
        </cac:AccountingSupplierParty>
    </xsl:template>

    <!--Accounting Customer Party main template -->
    <xsl:template match="cac:AccountingCustomerParty">
        <!--Variables-->
        <xsl:variable name="SchemeIDEndpointID" select="cac:Party/cbc:EndpointID/@schemeID"/>
        <xsl:variable name="SchemeIDEndpointIDValue" select="cac:Party/cbc:EndpointID"/>

        <xsl:variable name="SchemeIDPartyIdentificationID" select="cac:Party/cac:PartyIdentification/cbc:ID[@schemeID != 'GLN'][1]/@schemeID"/>
        <xsl:variable name="SchemeIDPartyIdentificationIDValue" select="cac:Party/cac:PartyIdentification/cbc:ID[@schemeID != 'GLN'][1]"/>

        <xsl:variable name="SchemeIDCompanyID" select="cac:Party/cac:PartyLegalEntity/cbc:CompanyID/@schemeID"/>
        <xsl:variable name="SchemeIDCompanyIDValue" select="cac:Party/cac:PartyLegalEntity/cbc:CompanyID"/>


        <!--Variables for the Customer class-->
        <cac:AccountingCustomerParty>
            <cac:Party>
                <!-- if EndpointID is found on seller endPointid is mapped-->
                <xsl:if test="string($SchemeIDEndpointIDValue)">

                    <!-- Call the mapping template -->
                    <xsl:call-template name="SchemeIDmapping">
                        <xsl:with-param name="ElementName" select="'cbc:EndpointID'"/>
                        <xsl:with-param name="SchemeID" select="$SchemeIDEndpointID"/>
                        <xsl:with-param name="SchemeIDValue" select="$SchemeIDEndpointIDValue"/>
                    </xsl:call-template>

                </xsl:if>
                <!--If GlobalID for the Customer is found the PartyIdentification class is created on behalf of that otherwise on the ram:ID of the seller otherwise nothing-->
                <xsl:choose>
                    <xsl:when test="string($SchemeIDPartyIdentificationIDValue)">

                        <cac:PartyIdentification>
                            <xsl:call-template name="SchemeIDmapping">
                                <xsl:with-param name="ElementName" select="'cbc:ID'"/>
                                <xsl:with-param name="SchemeID" select="$SchemeIDPartyIdentificationID"/>
                                <xsl:with-param name="SchemeIDValue" select="$SchemeIDPartyIdentificationIDValue"/>
                            </xsl:call-template>
                        </cac:PartyIdentification>

                    </xsl:when>
                    <xsl:when test="string(cac:PartyIdentification/cbc:ID)">
                        <cac:PartyIdentification>
                            <cbc:ID>
                                <xsl:value-of select="cac:PartyIdentification/cbc:ID"/>
                            </cbc:ID>
                        </cac:PartyIdentification>
                    </xsl:when>
                </xsl:choose>
                <!--If CII Customer PartyName is present it is mapped to BIS PArtyname-->
                <xsl:if test="string(cac:Party/cac:PartyName/cbc:Name)">
                    <cac:PartyName>
                        <cbc:Name>
                            <xsl:value-of select="cac:Party/cac:PartyName/cbc:Name"/>
                        </cbc:Name>
                    </cac:PartyName>
                </xsl:if>
                <!--Customer postal address is always created because country code is mandatory-->
                <cac:PostalAddress>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:StreetName)">
                        <cbc:StreetName>
                            <xsl:choose>
                                <xsl:when test="cac:Party/cac:PostalAddress/cbc:BuildingNumber">
                                    <xsl:value-of select="concat(cac:Party/cac:PostalAddress/cbc:StreetName, ' ', cac:Party/cac:PostalAddress/cbc:BuildingNumber)"/>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:StreetName"/>
                                </xsl:otherwise>
                            </xsl:choose>
                        </cbc:StreetName>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:AdditionalStreetName)">
                        <cbc:AdditionalStreetName>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:AdditionalStreetName"/>
                        </cbc:AdditionalStreetName>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:CityName)">
                        <cbc:CityName>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:CityName"/>
                        </cbc:CityName>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:PostalZone)">
                        <cbc:PostalZone>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:PostalZone"/>
                        </cbc:PostalZone>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cbc:CountrySubentity)">
                        <cbc:CountrySubentity>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:CountrySubentity"/>
                        </cbc:CountrySubentity>
                    </xsl:if>
                    <xsl:if test="string(cac:Party/cac:PostalAddress/cac:AddressLine/cbc:Line)">
                        <cac:AddressLine>
                            <cbc:Line>
                                <xsl:value-of select="cac:Party/cac:PostalAddress/cac:AddressLine/cbc:Line"/>
                            </cbc:Line>
                        </cac:AddressLine>
                    </xsl:if>
                    <!--Mandatory is address class-->
                    <cac:Country>
                        <cbc:IdentificationCode>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cac:Country/cbc:IdentificationCode"/>
                        </cbc:IdentificationCode>
                    </cac:Country>
                </cac:PostalAddress>

                <xsl:choose>
                    <xsl:when test="$ReverseChargeCount > 0">
                        <cac:PartyTaxScheme>
                            <cbc:CompanyID>
                                <xsl:value-of select="cac:Party/cac:PartyIdentification/cbc:ID"/>
                            </cbc:CompanyID>
                            <cac:TaxScheme>
                                <cbc:ID>VAT</cbc:ID>
                            </cac:TaxScheme>
                        </cac:PartyTaxScheme>
                    </xsl:when>
                    <xsl:when test="cac:Party/cac:PartyTaxScheme/cbc:CompanyID[@schemeID ='DK:SE']">
                        <cac:PartyTaxScheme>
                            <cbc:CompanyID>
                                <xsl:value-of select="cac:Party/cac:PartyTaxScheme/cbc:CompanyID[@schemeID ='DK:SE']"/>
                            </cbc:CompanyID>
                            <cac:TaxScheme>
                                <cbc:ID>VAT</cbc:ID>
                            </cac:TaxScheme>
                        </cac:PartyTaxScheme>
                    </xsl:when>
                    <xsl:when test="cac:Party/cac:PartyTaxScheme/cbc:CompanyID[@schemeID !='DK:SE']">
                        <cac:PartyTaxScheme>
                            <cbc:CompanyID>
                                <xsl:value-of select="cac:Party/cac:PartyTaxScheme/cbc:CompanyID[@schemeID !='DK:SE']"/>
                            </cbc:CompanyID>
                            <cac:TaxScheme>
                                <cbc:ID>TAX</cbc:ID>
                            </cac:TaxScheme>
                        </cac:PartyTaxScheme>
                    </xsl:when>
                </xsl:choose>

                <!-- PartyLegalEntity will always be present for the Customer because the company registration name is mandatory-->
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName>
                        <xsl:choose>
                            <xsl:when test="string(cac:Party/cac:PartyLegalEntity/cbc:RegistrationName) != ''">
                                <xsl:value-of select="cac:Party/cac:PartyLegalEntity/cbc:RegistrationName"/>
                            </xsl:when>
                            <xsl:when test="string(cac:Party/cac:PartyName/cbc:Name) != ''">
                                <xsl:value-of select="cac:Party/cac:PartyName/cbc:Name"/>
                            </xsl:when>
                            <xsl:otherwise>
                                <xsl:text>n/a</xsl:text>
                            </xsl:otherwise>
                        </xsl:choose>
                    </cbc:RegistrationName>
                    <!-- Call the mapping template -->
                    <xsl:if test="cbc:CompanyID">
                        <xsl:call-template name="SchemeIDmapping">
                            <xsl:with-param name="ElementName" select="'cbc:CompanyID'"/>
                            <xsl:with-param name="SchemeID" select="$SchemeIDCompanyID"/>
                            <xsl:with-param name="SchemeIDValue" select="$SchemeIDCompanyIDValue"/>
                        </xsl:call-template>
                    </xsl:if>

                </cac:PartyLegalEntity>
                <!--Contact class will be created if the defined trad contact if present in the CII Invoice-->
                <xsl:if test="normalize-space(cac:Party/cac:Contact/cbc:Name) != '' or normalize-space(cac:Party/cac:Contact/cbc:Telephone) != '' or normalize-space(cac:Party/cac:Contact/cbc:ElectronicMail) != ''">
                    <cac:Contact>
                        <xsl:if test="normalize-space(cac:Party/cac:Contact/cbc:Name) != ''">
                            <cbc:Name>
                                <xsl:value-of select="cac:Party/cac:Contact/cbc:Name"/>
                            </cbc:Name>
                        </xsl:if>
                        <xsl:if test="normalize-space(cac:Party/cac:Contact/cbc:Telephone) != ''">
                            <cbc:Telephone>
                                <xsl:value-of select="cac:Party/cac:Contact/cbc:Telephone"/>
                            </cbc:Telephone>
                        </xsl:if>
                        <xsl:if test="normalize-space(cac:Party/cac:Contact/cbc:ElectronicMail) != ''">
                            <cbc:ElectronicMail>
                                <xsl:value-of select="cac:Party/cac:Contact/cbc:ElectronicMail"/>
                            </cbc:ElectronicMail>
                        </xsl:if>
                    </cac:Contact>
                </xsl:if>
            </cac:Party>
        </cac:AccountingCustomerParty>
    </xsl:template>

    <!--Payee Party main template -->
    <xsl:template match="cac:PayeeParty">
        <!--Variables-->
        <xsl:variable name="SchemeIDCompanyID" select="cac:PartyLegalEntity/cbc:CompanyID/@schemeID"/>
        <xsl:variable name="SchemeIDCompanyIDValue" select="cac:PartyLegalEntity/cbc:CompanyID"/>

        <!--Variables for the Payee class-->
        <cac:PayeeParty>
            <!--If ram:ID of the seller present otherwise nothing-->
            <xsl:if test="string(cac:PartyIdentification/cbc:ID)">
                <cac:PartyIdentification>
                    <cbc:ID schemeID="SEPA">
                        <xsl:value-of select="cac:PartyIdentification/cbc:ID"/>
                    </cbc:ID>
                </cac:PartyIdentification>
            </xsl:if>
            <!--Mandatory in PEPPOL BIS-->
            <cac:PartyName>
                <cbc:Name>
                    <!-- cac:PartyName is not mandatory in OIOUBL2.1 -->
                    <xsl:choose>
                        <xsl:when test="cac:PartyName/cbc:Name">
                            <xsl:value-of select="cac:PartyName/cbc:Name"/>
                        </xsl:when>
                        <xsl:when test="../cac:AccountingSupplierParty/cac:Party/cac:PartyName/cbc:Name">
                            <xsl:value-of select="../cac:AccountingSupplierParty/cac:Party/cac:PartyName/cbc:Name"/>
                        </xsl:when>
                    </xsl:choose>
                </cbc:Name>
            </cac:PartyName>
            <!-- PartyLegalEntity will always be present for the supplier because the company registration name is mandatory-->
            <cac:PartyLegalEntity>

                <xsl:if test="string($SchemeIDCompanyIDValue)">
                    <!-- Call the mapping template -->
                    <xsl:call-template name="SchemeIDmapping">
                        <xsl:with-param name="ElementName" select="'cbc:CompanyID'"/>
                        <xsl:with-param name="SchemeID" select="$SchemeIDCompanyID"/>
                        <xsl:with-param name="SchemeIDValue" select="$SchemeIDCompanyIDValue"/>
                    </xsl:call-template>
                </xsl:if>

            </cac:PartyLegalEntity>

        </cac:PayeeParty>
    </xsl:template>

    <!--Delivery header main template -->
    <xsl:template match="cac:Delivery">
        <!--Variables-->
        <xsl:variable name="SchemeIDDeliveryLocationID" select="cac:DeliveryLocation/cbc:ID/@schemeID"/>
        <xsl:variable name="SchemeIDDeliveryLocationIDValue" select="cac:DeliveryLocation/cbc:ID"/>
        <!--Variables for the Delivery class-->
        <cac:Delivery>
            <xsl:if test="string(cbc:ActualDeliveryDate)">
                <cbc:ActualDeliveryDate>
                    <xsl:value-of select="cbc:ActualDeliveryDate"/>
                </cbc:ActualDeliveryDate>
            </xsl:if>
            <!--Delivery Address address is always created because country code is mandatory-->
            <xsl:if test="cac:DeliveryLocation">
                <cac:DeliveryLocation>
                    <!-- if GlobalID is found on Delivery endPointid is mapped-->
                    <xsl:if test="string($SchemeIDDeliveryLocationIDValue)">
                        <!-- Call the mapping template -->
                        <xsl:call-template name="SchemeIDmapping">
                            <xsl:with-param name="ElementName" select="'cbc:ID'"/>
                            <xsl:with-param name="SchemeID" select="$SchemeIDDeliveryLocationID"/>
                            <xsl:with-param name="SchemeIDValue" select="$SchemeIDDeliveryLocationIDValue"/>
                        </xsl:call-template>
                    </xsl:if>
                    <cac:Address>
                        <xsl:if test="string(cac:DeliveryLocation/cac:Address/cbc:StreetName)">
                            <cbc:StreetName>
                                <xsl:choose>
                                    <xsl:when test="cac:DeliveryLocation/cac:Address/cbc:BuildingNumber">
                                        <xsl:value-of select="concat(cac:DeliveryLocation/cac:Address/cbc:StreetName, ' ', cac:DeliveryLocation/cac:Address/cbc:BuildingNumber)"/>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:value-of select="cac:DeliveryLocation/cac:Address/cbc:StreetName"/>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </cbc:StreetName>
                        </xsl:if>
                        <xsl:if test="string(cac:DeliveryLocation/cac:Address/cbc:AdditionalStreetName)">
                            <cbc:AdditionalStreetName>
                                <xsl:value-of select="cac:DeliveryLocation/cac:Address/cbc:AdditionalStreetName"/>
                            </cbc:AdditionalStreetName>
                        </xsl:if>
                        <xsl:if test="string(cac:DeliveryLocation/cac:Address/cbc:CityName)">
                            <cbc:CityName>
                                <xsl:value-of select="cac:DeliveryLocation/cac:Address/cbc:CityName"/>
                            </cbc:CityName>
                        </xsl:if>
                        <xsl:if test="string(cac:DeliveryLocation/cac:Address/cbc:PostalZone)">
                            <cbc:PostalZone>
                                <xsl:value-of select="cac:DeliveryLocation/cac:Address/cbc:PostalZone"/>
                            </cbc:PostalZone>
                        </xsl:if>
                        <xsl:if test="string(cac:DeliveryLocation/cac:Address/cbc:CountrySubentity)">
                            <cbc:CountrySubentity>
                                <xsl:value-of select="cac:DeliveryLocation/cac:Address/cbc:CountrySubentity"/>
                            </cbc:CountrySubentity>
                        </xsl:if>
                        <xsl:if test="string(cac:DeliveryLocation/cac:Address/cac:AddressLine/cbc:Line)">
                            <cac:AddressLine>
                                <cbc:Line>
                                    <xsl:value-of select="cac:DeliveryLocation/cac:Address/cac:AddressLine/cbc:Line"/>
                                </cbc:Line>
                            </cac:AddressLine>
                        </xsl:if>
                        <!--Mandatory is address class-->
                        <cac:Country>
                            <cbc:IdentificationCode>
                                <xsl:value-of select="cac:DeliveryLocation/cac:Address/cac:Country/cbc:IdentificationCode"/>
                            </cbc:IdentificationCode>
                        </cac:Country>
                    </cac:Address>
                </cac:DeliveryLocation>
            </xsl:if>
            <xsl:if test="string(cac:DeliveryParty/cac:PartyName/cbc:Name)">
                <cac:DeliveryParty>
                    <cac:PartyName>
                        <cbc:Name>
                            <xsl:value-of select="cac:DeliveryParty/cac:PartyName/cbc:Name"/>
                        </cbc:Name>
                    </cac:PartyName>
                </cac:DeliveryParty>
            </xsl:if>
        </cac:Delivery>
    </xsl:template>

    <!--Payment Means header main template -->
    <xsl:template match="cac:PaymentMeans">
        <xsl:if test="position() = 1">
            <cac:PaymentMeans>
                <cbc:PaymentMeansCode>
                    <xsl:value-of select="cbc:PaymentMeansCode"/>
                </cbc:PaymentMeansCode>
                <xsl:choose>
                    <!-- Test for the first condition set -->
                    <xsl:when test="cbc:PaymentMeansCode='93' and (cbc:PaymentID='71' or cbc:PaymentID='73' or cbc:PaymentID='75') or cbc:PaymentMeansCode='50' and (cbc:PaymentID='01' or cbc:PaymentID='04' or cbc:PaymentID='15')">
                        <cbc:PaymentID>
                            <xsl:choose>
                                <!-- Check if cbc:InstructionID has a value -->
                                <xsl:when test="cbc:InstructionID">
                                    <xsl:value-of select="concat(cbc:PaymentID, '#', cbc:InstructionID)"/>
                                </xsl:when>
                                <!-- Check if cbc:InstructionNote has a value -->
                                <xsl:when test="cbc:InstructionNote">
                                    <xsl:value-of select="concat(cbc:PaymentID, '#', cbc:InstructionNote)"/>
                                </xsl:when>
                                <!-- Default value if neither cbc:InstructionID nor cbc:InstructionNote is present -->
                                <xsl:otherwise>
                                    <xsl:value-of select="concat(cbc:PaymentID, '#000000000000000')"/>
                                </xsl:otherwise>
                            </xsl:choose>
                        </cbc:PaymentID>
                        <cac:PayeeFinancialAccount>
                            <cbc:ID>
                                <xsl:choose>
                                    <!-- If PaymentMeansCode is 93, use and pad cac:CreditAccount/cbc:AccountID -->
                                    <xsl:when test="cbc:PaymentMeansCode = '93'">
                                        <xsl:value-of select="format-number(number(cac:CreditAccount/cbc:AccountID), '00000000')"/>
                                    </xsl:when>
                                    <!-- If PaymentMeansCode is 50, use and pad cac:PayeeFinancialAccount/cbc:ID -->
                                    <xsl:when test="cbc:PaymentMeansCode = '50'">
                                        <xsl:value-of select="format-number(number(cac:PayeeFinancialAccount/cbc:ID), '0000000')"/>
                                    </xsl:when>
                                    <!-- Default value -->
                                    <xsl:otherwise>
                                        <xsl:text>00000000</xsl:text>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </cbc:ID>
                        </cac:PayeeFinancialAccount>
                    </xsl:when>

                    <!-- Default case for all other conditions -->
                    <xsl:otherwise>
                        <cac:PayeeFinancialAccount>
                            <cbc:ID>
                                <xsl:value-of select="cac:PayeeFinancialAccount/cbc:ID"/>
                            </cbc:ID>
                            <xsl:if test="cbc:Name">
                                <cbc:Name>
                                    <xsl:value-of select="cbc:Name"/>
                                </cbc:Name>
                            </xsl:if>
                            <cac:FinancialInstitutionBranch>
                                <cbc:ID>
                                    <xsl:value-of select="cac:PayeeFinancialAccount/cac:FinancialInstitutionBranch/cac:FinancialInstitution/cbc:ID"/>
                                </cbc:ID>
                            </cac:FinancialInstitutionBranch>
                        </cac:PayeeFinancialAccount>
                    </xsl:otherwise>
                </xsl:choose>
            </cac:PaymentMeans>
        </xsl:if>
    </xsl:template>

    <!--Payment Terms header main template -->
    <xsl:template match="cac:PaymentTerms">
        <cac:PaymentTerms>
            <cbc:Note>
                <xsl:choose>
                    <xsl:when test="cbc:Note">
                        <xsl:value-of select="cbc:Note"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:text>Net</xsl:text>
                    </xsl:otherwise>
                </xsl:choose>
            </cbc:Note>
        </cac:PaymentTerms>
    </xsl:template>

    <!--AllowanceCharge header main template -->
    <xsl:template match="/inv:Invoice/cac:AllowanceCharge | /cre:CreditNote/cac:AllowanceCharge">
        <cac:AllowanceCharge>
            <!--Mandatory-->
            <cbc:ChargeIndicator>
                <xsl:value-of select="cbc:ChargeIndicator"/>
            </cbc:ChargeIndicator>
            <xsl:if test="cbc:AllowanceChargeReasonCode">
                <cbc:AllowanceChargeReasonCode>
                    <xsl:value-of select="cbc:AllowanceChargeReasonCode"/>
                </cbc:AllowanceChargeReasonCode>
            </xsl:if>
            <xsl:choose>
                <xsl:when test="cbc:AllowanceChargeReason">
                    <cbc:AllowanceChargeReason>
                        <xsl:value-of select="cbc:AllowanceChargeReason"/>
                    </cbc:AllowanceChargeReason>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="'n/a'"/>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:if test="cbc:MultiplierFactorNumeric">
                <cbc:MultiplierFactorNumeric>
                    <xsl:value-of select="format-number(cbc:MultiplierFactorNumeric * 100, '0.00')"/>
                </cbc:MultiplierFactorNumeric>
            </xsl:if>
            <!--Mandatory-->
            <cbc:Amount currencyID="{$DocumentCurrencyCode}">
                <xsl:value-of select="format-number(cbc:Amount, '0.00')"/>
            </cbc:Amount>
            <xsl:if test="cbc:BaseAmount">
                <cbc:BaseAmount currencyID="{$DocumentCurrencyCode}">
                    <xsl:value-of select="format-number(cbc:BaseAmount, '0.00')"/>
                </cbc:BaseAmount>
            </xsl:if>
            <!--Mandatory-->
            <cac:TaxCategory>
                <cbc:ID>
                    <xsl:call-template name="mapTaxCode">
                        <xsl:with-param name="taxString" select="cac:TaxCategory/cbc:ID"/>
                    </xsl:call-template>
                </cbc:ID>
                <xsl:if test="cac:TaxCategory/cbc:Percent">
                    <cbc:Percent>
                        <xsl:value-of select="cac:TaxCategory/cbc:Percent"/>
                    </cbc:Percent>
                </xsl:if>
                <cac:TaxScheme>
                    <cbc:ID>VAT</cbc:ID>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:AllowanceCharge>
    </xsl:template>

    <!--TaxTotal header main template -->
    <xsl:template match="cac:TaxTotal">
        <xsl:param name="CurrencyCode"/>
        <cac:TaxTotal>
            <cbc:TaxAmount>
                <xsl:attribute name="currencyID" select="$DocumentCurrencyCode"/>
                <xsl:value-of select="format-number(cbc:TaxAmount[@currencyID = $DocumentCurrencyCode], '0.00')"/>
            </cbc:TaxAmount>

            <!--            <xsl:if test="$CurrencyCode = $DocumentCurrencyCode">-->

            <!-- Conditionally create TaxSubtotal for StandardRated -->
            <xsl:if test="$StandardRatedCount > 0">
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount>
                        <xsl:attribute name="currencyID" select="$DocumentCurrencyCode"/>
                        <xsl:value-of select="format-number($SumLineExtensionAmountS, '0.00')"/>
                    </cbc:TaxableAmount>
                    <cbc:TaxAmount>
                        <xsl:attribute name="currencyID" select="$DocumentCurrencyCode"/>
                        <xsl:value-of select="format-number($SumTaxAmount, '0.00')"/>
                    </cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID>S</cbc:ID>
                        <cbc:Percent>25.00</cbc:Percent>
                        <cac:TaxScheme>
                            <cbc:ID>VAT</cbc:ID>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>
            </xsl:if>

            <!-- Conditionally create TaxSubtotal for ZeroRated -->
            <xsl:if test="$ZeroRatedCount > 0">
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount>
                        <xsl:attribute name="currencyID" select="$DocumentCurrencyCode"/>
                        <xsl:value-of select="format-number($SumLineExtensionAmountZ, '0.00')"/>
                    </cbc:TaxableAmount>
                    <cbc:TaxAmount>
                        <xsl:attribute name="currencyID" select="$DocumentCurrencyCode"/>
                        <xsl:value-of select="format-number($SumLineExtensionAmountZ * 0, '0.00')"/>
                    </cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID>Z</cbc:ID>
                        <cbc:Percent>0.00</cbc:Percent>
                        <cac:TaxScheme>
                            <cbc:ID>VAT</cbc:ID>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>
            </xsl:if>

            <!-- Conditionally create TaxSubtotal for ReverseCharge -->
            <xsl:if test="$ReverseChargeCount > 0">
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount>
                        <xsl:attribute name="currencyID" select="$DocumentCurrencyCode"/>
                        <xsl:value-of select="format-number($SumLineExtensionAmountAE, '0.00')"/>
                    </cbc:TaxableAmount>
                    <cbc:TaxAmount>
                        <xsl:attribute name="currencyID" select="$DocumentCurrencyCode"/>
                        <xsl:value-of select="format-number($SumLineExtensionAmountAE * 0, '0.00')"/>
                    </cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID>AE</cbc:ID>
                        <cbc:Percent>0.00</cbc:Percent>
                        <cbc:TaxExemptionReason>Reverse charge</cbc:TaxExemptionReason>
                        <cac:TaxScheme>
                            <cbc:ID>VAT</cbc:ID>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>
            </xsl:if>

            <!--            </xsl:if>-->
        </cac:TaxTotal>
    </xsl:template>

    <!--Totals header main template -->
    <xsl:template match="cac:LegalMonetaryTotal">
        <cac:LegalMonetaryTotal>
            <!--Mandatory-->
            <cbc:LineExtensionAmount currencyID="{$DocumentCurrencyCode}">
                <xsl:value-of select="format-number($SumLineExtensionAmount, '0.00')"/>
            </cbc:LineExtensionAmount>
            <!--Mandatory-->
            <cbc:TaxExclusiveAmount currencyID="{$DocumentCurrencyCode}">
                <xsl:value-of select="format-number($TaxExclusiveAmount, '0.00')"/>
            </cbc:TaxExclusiveAmount>
            <!--Mandatory-->
            <cbc:TaxInclusiveAmount currencyID="{$DocumentCurrencyCode}">
                <xsl:value-of select="format-number($TaxInclusiveAmount, '0.00')"/>
            </cbc:TaxInclusiveAmount>
            <xsl:if test="$SumAllowanceAmount > 0">
                <cbc:AllowanceTotalAmount currencyID="{$DocumentCurrencyCode}">
                    <xsl:value-of select="format-number($SumAllowanceAmount, '0.00')"/>
                </cbc:AllowanceTotalAmount>
            </xsl:if>
            <xsl:if test="$SumChargeAmount > 0">
                <cbc:ChargeTotalAmount currencyID="{$DocumentCurrencyCode}">
                    <xsl:value-of select="format-number($SumChargeAmount, '0.00')"/>
                </cbc:ChargeTotalAmount>
            </xsl:if>
            <xsl:if test="cbc:PrepaidAmount">
                <cbc:PrepaidAmount currencyID="{$DocumentCurrencyCode}">
                    <xsl:value-of select="format-number(cbc:PrepaidAmount, '0.00')"/>
                </cbc:PrepaidAmount>
            </xsl:if>
            <xsl:if test="cbc:PayableRoundingAmount">
                <cbc:PayableRoundingAmount currencyID="{$DocumentCurrencyCode}">
                    <xsl:value-of select="cbc:PayableRoundingAmount"/>
                </cbc:PayableRoundingAmount>
            </xsl:if>
            <!--Mandatory-->
            <cbc:PayableAmount currencyID="{$DocumentCurrencyCode}">
                <xsl:value-of select="format-number(cbc:PayableAmount, '0.00')"/>
            </cbc:PayableAmount>
        </cac:LegalMonetaryTotal>
    </xsl:template>

    <!-- InvoiceLines and CreditNoteLines -->
    <xsl:template match="cac:InvoiceLine | cac:CreditNoteLine">
        <!-- Determine whether to output cac:InvoiceLine or cac:CreditNoteLine -->
        <xsl:element name="cac:{local-name()}">
            <!-- Variables for line level info -->
            <xsl:variable name="UnitCodeScheme">
                <xsl:choose>
                    <xsl:when test="self::cac:InvoiceLine">
                        <xsl:value-of select="cbc:InvoicedQuantity/@unitCode"/>
                    </xsl:when>
                    <xsl:when test="self::cac:CreditNoteLine">
                        <xsl:value-of select="cbc:CreditedQuantity/@unitCode"/>
                    </xsl:when>
                </xsl:choose>
            </xsl:variable>

            <!-- Mandatory -->
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>

            <xsl:if test="cbc:Note">
                <cbc:Note>
                    <xsl:value-of select="cbc:Note"/>
                </cbc:Note>
            </xsl:if>

            <!-- Mandatory Quantity -->
            <xsl:choose>
                <xsl:when test="self::cac:InvoiceLine">
                    <cbc:InvoicedQuantity unitCode="{$UnitCodeScheme}">
                        <xsl:value-of select="cbc:InvoicedQuantity"/>
                    </cbc:InvoicedQuantity>
                </xsl:when>
                <xsl:when test="self::cac:CreditNoteLine">
                    <cbc:CreditedQuantity unitCode="{$UnitCodeScheme}">
                        <xsl:value-of select="cbc:CreditedQuantity"/>
                    </cbc:CreditedQuantity>
                </xsl:when>
            </xsl:choose>

            <!-- Mandatory LineExtensionAmount -->
            <cbc:LineExtensionAmount currencyID="{$DocumentCurrencyCode}">
                <xsl:choose>
                    <xsl:when test="cbc:LineExtensionAmount &lt; 0">
                        <xsl:value-of select="'0.00'"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="format-number(cbc:LineExtensionAmount, '0.00')"/>
                    </xsl:otherwise>
                </xsl:choose>
            </cbc:LineExtensionAmount>

            <xsl:if test="string(cbc:AccountingCost)">
                <cbc:AccountingCost>
                    <xsl:value-of select="cbc:AccountingCost"/>
                </cbc:AccountingCost>
            </xsl:if>

            <!-- Inserting OrderReference if present -->
            <xsl:apply-templates select="cac:OrderLineReference"/>

            <!-- Inserting Additional document on line level if present -->
            <xsl:apply-templates select="cac:DocumentReference"/>

            <!-- Inserting Line AllowanceCharge if present -->
            <xsl:apply-templates select="cac:AllowanceCharge"/>

            <xsl:if test="cbc:LineExtensionAmount &lt; 0">
                <cac:AllowanceCharge>
                    <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                    <cbc:AllowanceChargeReason>Discount</cbc:AllowanceChargeReason>
                    <cbc:Amount currencyID="{$DocumentCurrencyCode}">
                        <xsl:value-of select="format-number(abs(cbc:LineExtensionAmount), '0.00')"/>
                    </cbc:Amount>
                </cac:AllowanceCharge>
            </xsl:if>

            <!-- Inserting Item information on line level -->
            <xsl:apply-templates select="cac:Item"/>

            <!-- Inserting Price information -->
            <xsl:apply-templates select="cac:Price"/>

        </xsl:element>
    </xsl:template>

    <!--Templates for Line level-->
    <!--Building OrderLine if present-->
    <xsl:template match="cac:OrderLineReference">
        <cac:OrderLineReference>
            <cbc:LineID>
                <xsl:value-of select="cbc:LineID"/>
            </cbc:LineID>
        </cac:OrderLineReference>
    </xsl:template>

    <!--Building AdditionalReference if present-->
    <xsl:template match="cac:DocumentReference">
        <cac:DocumentReference>
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>
            <xsl:if test="cbc:DocumentTypeCode">
                <cbc:DocumentTypeCode>
                    <!-- Apply a mapping template to cbc:DocumentTypeCode -->
                    <xsl:apply-templates select="cbc:DocumentTypeCode"/>
                </cbc:DocumentTypeCode>
            </xsl:if>
        </cac:DocumentReference>
    </xsl:template>

    <!--Building Line AllowanceCharge -->
    <xsl:template match="cac:InvoiceLine/cac:AllowanceCharge">
        <cac:AllowanceCharge>
            <!--Mandatory-->
            <cbc:ChargeIndicator>
                <xsl:value-of select="cbc:ChargeIndicator"/>
            </cbc:ChargeIndicator>
            <xsl:if test="cbc:AllowanceChargeReasonCode">
                <cbc:AllowanceChargeReasonCode>
                    <xsl:value-of select="cbc:AllowanceChargeReasonCode"/>
                </cbc:AllowanceChargeReasonCode>
            </xsl:if>
            <xsl:choose>
                <xsl:when test="cbc:AllowanceChargeReason">
                    <cbc:AllowanceChargeReason>
                        <xsl:value-of select="cbc:AllowanceChargeReason"/>
                    </cbc:AllowanceChargeReason>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="'n/a'"/>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:if test="cbc:MultiplierFactorNumeric">
                <cbc:MultiplierFactorNumeric>
                    <xsl:value-of select="format-number(cbc:MultiplierFactorNumeric * 100, '0.00')"/>
                </cbc:MultiplierFactorNumeric>
            </xsl:if>
            <!--Mandatory-->
            <cbc:Amount currencyID="{$DocumentCurrencyCode}">
                <xsl:value-of select="format-number(cbc:Amount, '0.00')"/>
            </cbc:Amount>
            <xsl:if test="cbc:BaseAmount">
                <cbc:BaseAmount currencyID="{$DocumentCurrencyCode}">
                    <xsl:value-of select="format-number(cbc:BaseAmount, '0.00')"/>
                </cbc:BaseAmount>
            </xsl:if>
        </cac:AllowanceCharge>
    </xsl:template>

    <!--Building Item main template -->
    <xsl:template match="cac:Item">
        <cac:Item>
            <xsl:if test="string(cbc:Description) != ''">
                <cbc:Description>
                    <xsl:value-of select="cbc:Description"/>
                </cbc:Description>
            </xsl:if>
            <!--Mandatory Item Name-->
            <cbc:Name>
                <xsl:value-of select="cbc:Name"/>
            </cbc:Name>
            <xsl:if test="cac:BuyersItemIdentification">
                <cac:BuyersItemIdentification>
                    <cbc:ID>
                        <xsl:value-of select="cac:BuyersItemIdentification/cbc:ID"/>
                    </cbc:ID>
                </cac:BuyersItemIdentification>
            </xsl:if>
            <xsl:if test="cac:SellersItemIdentification">
                <cac:SellersItemIdentification>
                    <cbc:ID>
                        <xsl:value-of select="cac:SellersItemIdentification/cbc:ID"/>
                    </cbc:ID>
                </cac:SellersItemIdentification>
            </xsl:if>
            <xsl:if test="cac:StandardItemIdentification">
                <cac:StandardItemIdentification>
                    <!-- TODO: find the correct way to implement schemeID attribute? See: https://docs.peppol.eu/poacc/billing/3.0/codelist/ICD/ -->
                    <cbc:ID schemeID="0160">
                        <xsl:value-of select="cac:StandardItemIdentification/cbc:ID"/>
                    </cbc:ID>
                </cac:StandardItemIdentification>
            </xsl:if>
            <xsl:if test="cac:OriginCountry">
                <cac:OriginCountry>
                    <cbc:IdentificationCode>
                        <xsl:value-of select="cac:OriginCountry/cbc:IdentificationCode"/>
                    </cbc:IdentificationCode>
                </cac:OriginCountry>
            </xsl:if>
            <xsl:apply-templates select="cac:CommodityClassification"/>

            <!--Mandatory TAX on line level ClassifiedTaxCategory -->
            <xsl:apply-templates select="../cac:TaxTotal[1]/cac:TaxSubtotal/cac:TaxCategory"/>

            <xsl:if test="cac:AdditionalItemProperty">
                <cbc:AdditionalItemProperty>
                    <xsl:value-of select="cac:AdditionalItemProperty"/>
                </cbc:AdditionalItemProperty>
            </xsl:if>

        </cac:Item>
    </xsl:template>

    <xsl:template match="cac:CommodityClassification">
        <cac:CommodityClassification>
            <xsl:if test="cbc:ItemClassificationCode">
                <cbc:ItemClassificationCode>
                    <!-- TODO: create a mapping between OIOUBL listName and PEPPOL listID. See: https://docs.peppol.eu/poacc/billing/3.0/codelist/UNCL7143/ -->
                    <xsl:if test="cbc:ItemClassificationCode/@listName">
                        <xsl:attribute name="listID" select="cbc:ItemClassificationCode/@listName"/>
                    </xsl:if>
                    <xsl:if test="cbc:ItemClassificationCode/@listVersionID">
                        <xsl:attribute name="listVersionID" select="cbc:ItemClassificationCode/@listVersionID"/>
                    </xsl:if>
                    <xsl:value-of select="cbc:ItemClassificationCode"/>
                </cbc:ItemClassificationCode>
            </xsl:if>
        </cac:CommodityClassification>
    </xsl:template>

    <xsl:template match="cac:TaxTotal[1]/cac:TaxSubtotal/cac:TaxCategory">
        <cac:ClassifiedTaxCategory>
            <cbc:ID>
                <xsl:call-template name="mapTaxCode">
                    <xsl:with-param name="taxString" select="cbc:ID"/>
                </xsl:call-template>
            </cbc:ID>
            <xsl:if test="cbc:Percent">
                <cbc:Percent>
                    <xsl:value-of select="cbc:Percent"/>
                </cbc:Percent>
            </xsl:if>
            <cac:TaxScheme>
                <cbc:ID>VAT</cbc:ID>
            </cac:TaxScheme>
        </cac:ClassifiedTaxCategory>
    </xsl:template>
    <!--End Item templates-->

    <!--Price main template start-->
    <xsl:template match="cac:Price">
        <cac:Price>
            <cbc:PriceAmount currencyID="{$DocumentCurrencyCode}">
                <xsl:value-of select="cbc:PriceAmount"/>
            </cbc:PriceAmount>
            <xsl:if test="cbc:BaseQuantity">
                <cbc:BaseQuantity>
                    <xsl:if test="cbc:BaseQuantity/@unitCode">
                        <xsl:attribute name="unitCode" select="cbc:BaseQuantity/@unitCode"/>
                    </xsl:if>
                    <xsl:value-of select="cbc:BaseQuantity"/>
                </cbc:BaseQuantity>
            </xsl:if>
            <!--Inserting Price Allowance if a discount is found in CII-->
            <xsl:if test="cac:AllowanceCharge">
                <cac:AllowanceCharge>
                    <cbc:ChargeIndicator>
                        <xsl:value-of select="'false'"/>
                    </cbc:ChargeIndicator>
                    <cbc:Amount currencyID="{$DocumentCurrencyCode}">
                        <xsl:value-of select="cac:AllowanceCharge/cbc:Amount"/>
                    </cbc:Amount>
                </cac:AllowanceCharge>
            </xsl:if>
        </cac:Price>
    </xsl:template>

    <!--    &lt;!&ndash;End Price Template&ndash;&gt;-->
    <!--    &lt;!&ndash;End of Invoice&ndash;&gt;-->

    <!--Template for handling attribute SchemeID and value mappings between OIOUBL2.1 and PEPPOL BIS -->
    <xsl:template name="SchemeIDmapping">
        <xsl:param name="ElementName" select="'cbc:ID'"/>
        <xsl:param name="SchemeID"/>
        <xsl:param name="SchemeIDValue"/>

        <xsl:variable name="MappedSchemeID">
            <xsl:choose>
                <xsl:when test="$SchemeID = 'FR:SIRENE'">0002</xsl:when>
                <xsl:when test="$SchemeID = 'SE:ORGNR'">0007</xsl:when>
                <xsl:when test="$SchemeID = 'FR:SIRET'">0009</xsl:when>
                <xsl:when test="$SchemeID = 'FI:OVT'">0037</xsl:when>
                <xsl:when test="$SchemeID = 'DUNS'">0060</xsl:when>
                <xsl:when test="$SchemeID = 'GLN'">0088</xsl:when>
                <xsl:when test="$SchemeID = 'DK:P'">0096</xsl:when>
                <xsl:when test="$SchemeID = 'IT:FTI'">0097</xsl:when>
                <xsl:when test="$SchemeID = 'NL:KVK'">0106</xsl:when>
                <xsl:when test="$SchemeID = 'EU:NAL'">0130</xsl:when>
                <xsl:when test="$SchemeID = 'IT:SIA'">0135</xsl:when>
                <xsl:when test="$SchemeID = 'IT:SECETI'">0142</xsl:when>
                <xsl:when test="$SchemeID = 'AU:ABN'">0151</xsl:when>
                <xsl:when test="$SchemeID = 'CH:UIDB'">0183</xsl:when>
                <xsl:when test="$SchemeID = 'DK:CVR'">0184</xsl:when>
                <xsl:when test="$SchemeID = 'JP:SST'">0188</xsl:when>
                <xsl:when test="$SchemeID = 'NL:OINO'">0190</xsl:when>
                <xsl:when test="$SchemeID = 'EE:CC'">0191</xsl:when>
                <xsl:when test="$SchemeID = 'NO:ORG'">0192</xsl:when>
                <xsl:when test="$SchemeID = 'UBLBE'">0193</xsl:when>
                <xsl:when test="$SchemeID = 'SG:UEN'">0195</xsl:when>
                <xsl:when test="$SchemeID = 'IS:KTNR'">0196</xsl:when>
                <xsl:when test="$SchemeID = 'DK:ERST'">0198</xsl:when>
                <xsl:when test="$SchemeID = 'LEI'">0199</xsl:when>
                <xsl:when test="$SchemeID = 'LT:LEC'">0200</xsl:when>
                <xsl:when test="$SchemeID = 'IT:CUUO'">0201</xsl:when>
                <xsl:when test="$SchemeID = 'DE:LWID'">0204</xsl:when>
                <xsl:when test="$SchemeID = 'BE:EN'">0208</xsl:when>
                <xsl:when test="$SchemeID = 'IT:CFI'">0210</xsl:when>
                <xsl:when test="$SchemeID = 'IT:IVA'">0211</xsl:when>
                <xsl:when test="$SchemeID = 'FI:ORG'">0212</xsl:when>
                <xsl:when test="$SchemeID = 'FI:VAT'">0213</xsl:when>
                <xsl:when test="$SchemeID = 'FI:NSI'">0215</xsl:when>
                <xsl:when test="$SchemeID = 'FI:OVT2'">0216</xsl:when>
                <xsl:when test="$SchemeID = 'DK:CPR'">9901</xsl:when>
                <xsl:when test="$SchemeID = 'DK:CVR'">9902</xsl:when>
                <xsl:when test="$SchemeID = 'DK:SE'">9904</xsl:when>
                <xsl:when test="$SchemeID = 'DK:VANS'">9905</xsl:when>
                <xsl:when test="$SchemeID = 'IT:VAT'">9906</xsl:when>
                <xsl:when test="$SchemeID = 'IT:CF'">9907</xsl:when>
                <xsl:when test="$SchemeID = 'NO:ORGNR'">9908</xsl:when>
                <xsl:when test="$SchemeID = 'NO:VAT'">9909</xsl:when>
                <xsl:when test="$SchemeID = 'HU:VAT'">9910</xsl:when>
                <xsl:when test="$SchemeID = 'EU:VAT'">9912</xsl:when>
                <xsl:when test="$SchemeID = 'EU:REID'">9913</xsl:when>
                <xsl:when test="$SchemeID = 'AT:VAT'">9914</xsl:when>
                <xsl:when test="$SchemeID = 'AT:GOV'">9915</xsl:when>
                <xsl:when test="$SchemeID = 'AT:CID'">9916</xsl:when>
                <xsl:when test="$SchemeID = 'IS:KT'">9917</xsl:when>
                <xsl:when test="$SchemeID = 'IBAN'">9918</xsl:when>
                <xsl:when test="$SchemeID = 'AT:KUR'">9919</xsl:when>
                <xsl:when test="$SchemeID = 'ES:VAT'">9920</xsl:when>
                <xsl:when test="$SchemeID = 'IT:IPA'">9921</xsl:when>
                <xsl:when test="$SchemeID = 'AD:VAT'">9922</xsl:when>
                <xsl:when test="$SchemeID = 'AL:VAT'">9923</xsl:when>
                <xsl:when test="$SchemeID = 'BA:VAT'">9924</xsl:when>
                <xsl:when test="$SchemeID = 'BE:VAT'">9925</xsl:when>
                <xsl:when test="$SchemeID = 'BG:VAT'">9926</xsl:when>
                <xsl:when test="$SchemeID = 'CH:VAT'">9927</xsl:when>
                <xsl:when test="$SchemeID = 'CY:VAT'">9928</xsl:when>
                <xsl:when test="$SchemeID = 'CZ:VAT'">9929</xsl:when>
                <xsl:when test="$SchemeID = 'DE:VAT'">9930</xsl:when>
                <xsl:when test="$SchemeID = 'EE:VAT'">9931</xsl:when>
                <xsl:when test="$SchemeID = 'GB:VAT'">9932</xsl:when>
                <xsl:when test="$SchemeID = 'GR:VAT'">9933</xsl:when>
                <xsl:when test="$SchemeID = 'HR:VAT'">9934</xsl:when>
                <xsl:when test="$SchemeID = 'IE:VAT'">9935</xsl:when>
                <xsl:when test="$SchemeID = 'LI:VAT'">9936</xsl:when>
                <xsl:when test="$SchemeID = 'LT:VAT'">9937</xsl:when>
                <xsl:when test="$SchemeID = 'LU:VAT'">9938</xsl:when>
                <xsl:when test="$SchemeID = 'LV:VAT'">9939</xsl:when>
                <xsl:when test="$SchemeID = 'MC:VAT'">9940</xsl:when>
                <xsl:when test="$SchemeID = 'ME:VAT'">9941</xsl:when>
                <xsl:when test="$SchemeID = 'MK:VAT'">9942</xsl:when>
                <xsl:when test="$SchemeID = 'MT:VAT'">9943</xsl:when>
                <xsl:when test="$SchemeID = 'NL:VAT'">9944</xsl:when>
                <xsl:when test="$SchemeID = 'PL:VAT'">9945</xsl:when>
                <xsl:when test="$SchemeID = 'PT:VAT'">9946</xsl:when>
                <xsl:when test="$SchemeID = 'RO:VAT'">9947</xsl:when>
                <xsl:when test="$SchemeID = 'RS:VAT'">9948</xsl:when>
                <xsl:when test="$SchemeID = 'SI:VAT'">9949</xsl:when>
                <xsl:when test="$SchemeID = 'SK:VAT'">9950</xsl:when>
                <xsl:when test="$SchemeID = 'SM:VAT'">9951</xsl:when>
                <xsl:when test="$SchemeID = 'TR:VAT'">9952</xsl:when>
                <xsl:when test="$SchemeID = 'VA:VAT'">9953</xsl:when>
                <xsl:when test="$SchemeID = 'NL:OIN'">9954</xsl:when>
                <xsl:when test="$SchemeID = 'SE:VAT'">9955</xsl:when>
                <xsl:when test="$SchemeID = 'BE:CBE'">9956</xsl:when>
                <xsl:when test="$SchemeID = 'FR:VAT'">9957</xsl:when>
                <xsl:when test="$SchemeID = 'DE:LID'">9958</xsl:when>
                <xsl:otherwise><xsl:value-of select="$SchemeID"/></xsl:otherwise>
            </xsl:choose>
        </xsl:variable>

        <!-- Output the mapped ID with the mapped schemeID -->
        <xsl:element name="{$ElementName}">
            <xsl:attribute name="schemeID">
                <xsl:value-of select="$MappedSchemeID"/>
            </xsl:attribute>
            <xsl:value-of select="$SchemeIDValue"/>
        </xsl:element>

    </xsl:template>

    <!--Template for handling TaxCategory/ID value mappings between OIOUBL2.1 and PEPPOL BIS -->
    <xsl:template name="mapTaxCode">
        <xsl:param name="taxString"/>
        <xsl:choose>
            <xsl:when test="$taxString = 'StandardRated'">
                <xsl:text>S</xsl:text>
            </xsl:when>
            <xsl:when test="$taxString = 'ZeroRated'">
                <xsl:text>Z</xsl:text>
            </xsl:when>
            <xsl:when test="$taxString = 'ReverseCharge'">
                <xsl:text>AE</xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="$taxString"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="cac:DocumentReference/cbc:DocumentTypeCode">
        <cbc:DocumentTypeCode>
            <xsl:choose>
                <xsl:when test=". = 'ApplicationResponse'">
                    <xsl:text>916</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'Catalogue'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'CatalogueDeletion'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'CatalogueItemSpecificationUpdate'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'CatalogueItemUpdate'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'CataloguePricingUpdate'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'CataloguePriceUpdate'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'CatalogueRequest'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'CreditNote'">
                    <xsl:text>CD</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'Invoice'">
                    <xsl:text>916</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'Order'">
                    <xsl:text>130</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'OrderCancellation'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'OrderChange'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'OrderResponse'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'OrderResponseSimple'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'Reminder'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'Statement'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'Payment'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:when test=". = 'PersonalSecure'">
                    <xsl:text>ZZZ</xsl:text>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:text>ZZZ</xsl:text>
                </xsl:otherwise>
            </xsl:choose>
        </cbc:DocumentTypeCode>
    </xsl:template>

</xsl:stylesheet>