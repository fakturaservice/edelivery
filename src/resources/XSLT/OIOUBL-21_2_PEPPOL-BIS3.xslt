<?xml version="1.0" encoding="UTF-8"?>
<!--
******************************************************************************************************************

    Conversion from OIOUBL 2.1 to PEPPOL BIS3.

    Publisher:          Fakturaservice.dk

    Description:        General conversion of the OIOUBL 2.1 invoice and credit note syntax, to the PEPPOL BIS3 Billing syntax.
    Rights:             It can be used following the Common Creative License
                        Copyright (c) 2024. Fakturaservice A/S - All Rights Reserved
                        Unauthorized copying of this file, via any medium is strictly prohibited.
                        Proprietary and confidential
                        Written by Torben Wrang Laursen <twl@fakturaservice.dk>, June 2024

    Changed:20240620: First initial template creation.
    Changed:2024****: .....next update comment her....

******************************************************************************************************************
-->
<xsl:stylesheet version="2.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:oioubl="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
                xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                xmlns:sdt="urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2"
                xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xmlns:peppol="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
                exclude-result-prefixes="oioubl peppol sdt udt xsi">

    <xsl:output method="xml" encoding="UTF-8" indent="yes"/>
    <xsl:strip-space elements="*"/>

    <!-- Template to start processing the document from the root -->
    <xsl:template match="/">
        <xsl:apply-templates/>
    </xsl:template>

    <!-- Template to catch unsupported document types -->
    <xsl:template match="*">
        <Error>
            <Errortext>Fatal error: Unsupported document type! This stylesheet only supports conversion of OIOUBL 2.1 Invoice or CreditNote.</Errortext>
            <Input>
                <xsl:value-of select="."/>
            </Input>
        </Error>
    </xsl:template>

    <!-- Template match for the root element -->
    <xsl:template match="/oioubl:Invoice">
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
                 xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
                 xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
                 xmlns:ccts="urn:oasis:names:specification:ubl:schema:xsd:CoreComponentParameters-2"
                 xmlns:sdt="urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2"
                 xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
                 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                 xsi:schemaLocation="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2 UBL-Invoice-2.0.xsd">
            <cbc:CustomizationID>urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0</cbc:CustomizationID>
            <cbc:ProfileID>urn:fdc:peppol.eu:2017:poacc:billing:01:1.0</cbc:ProfileID>
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>
            <cbc:IssueDate>
                <xsl:value-of select="cbc:IssueDate"/>
            </cbc:IssueDate>
            <cbc:DueDate>
                <xsl:value-of select="cac:PaymentMeans[1]/cbc:PaymentDueDate"/>
            </cbc:DueDate>
            <cbc:InvoiceTypeCode>
                <xsl:value-of select="cbc:InvoiceTypeCode"/>
            </cbc:InvoiceTypeCode>
            <cbc:DocumentCurrencyCode>
                <xsl:value-of select="cbc:DocumentCurrencyCode"/>
            </cbc:DocumentCurrencyCode>
            <cac:OrderReference>
                <cbc:ID>
                    <xsl:value-of select="cac:OrderReference/cbc:ID"/>
                </cbc:ID>
            </cac:OrderReference>
            <xsl:apply-templates select="cac:AccountingSupplierParty"/>
            <xsl:apply-templates select="cac:AccountingCustomerParty"/>
            <cac:Delivery>
                <cbc:ActualDeliveryDate>
                    <xsl:value-of select="cac:Delivery/cbc:ActualDeliveryDate"/>
                </cbc:ActualDeliveryDate>
            </cac:Delivery>
            <cac:PaymentMeans>
                <cbc:PaymentMeansCode>
                    <xsl:value-of select="cac:PaymentMeans/cbc:PaymentMeansCode"/>
                </cbc:PaymentMeansCode>
                <cbc:PaymentID>
                    <xsl:value-of select="cac:PaymentMeans/cbc:PaymentID"/>
                </cbc:PaymentID>
                <cac:PayeeFinancialAccount>
                    <cbc:ID>
                        <xsl:value-of select="cac:PaymentMeans/cac:PayeeFinancialAccount/cbc:ID"/>
                    </cbc:ID>
                </cac:PayeeFinancialAccount>
            </cac:PaymentMeans>
            <cac:PaymentTerms>
                <cbc:Note>
                    <xsl:value-of select="cac:PaymentTerms/cbc:Note"/>
                </cbc:Note>
            </cac:PaymentTerms>
            <cac:TaxTotal>
                <cbc:TaxAmount currencyID="DKK">
                    <xsl:value-of select="cac:TaxTotal/cbc:TaxAmount"/>
                </cbc:TaxAmount>
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="DKK">
                        <xsl:value-of select="cac:TaxTotal/cac:TaxSubtotal/cbc:TaxableAmount"/>
                    </cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="DKK">
                        <xsl:value-of select="cac:TaxTotal/cac:TaxSubtotal/cbc:TaxAmount"/>
                    </cbc:TaxAmount>
                    <cac:TaxCategory>
                        <cbc:ID>
                            <xsl:value-of select="cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:ID"/>
                        </cbc:ID>
                        <cbc:Percent>
                            <xsl:value-of select="cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cbc:Percent"/>
                        </cbc:Percent>
                        <cac:TaxScheme>
                            <cbc:ID>
                                <xsl:value-of select="cac:TaxTotal/cac:TaxSubtotal/cac:TaxCategory/cac:TaxScheme/cbc:ID"/>
                            </cbc:ID>
                        </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>
            </cac:TaxTotal>
            <cac:LegalMonetaryTotal>
                <cbc:LineExtensionAmount currencyID="DKK">
                    <xsl:value-of select="cac:LegalMonetaryTotal/cbc:LineExtensionAmount"/>
                </cbc:LineExtensionAmount>
                <cbc:TaxExclusiveAmount currencyID="DKK">
                    <xsl:value-of select="cac:LegalMonetaryTotal/cbc:TaxExclusiveAmount"/>
                </cbc:TaxExclusiveAmount>
                <cbc:TaxInclusiveAmount currencyID="DKK">
                    <xsl:value-of select="cac:LegalMonetaryTotal/cbc:TaxInclusiveAmount"/>
                </cbc:TaxInclusiveAmount>
                <cbc:PayableAmount currencyID="DKK">
                    <xsl:value-of select="cac:LegalMonetaryTotal/cbc:PayableAmount"/>
                </cbc:PayableAmount>
            </cac:LegalMonetaryTotal>
            <xsl:apply-templates select="cac:InvoiceLine"/>
        </Invoice>
    </xsl:template>

    <!-- Template for AccountingSupplierParty -->
    <xsl:template match="cac:AccountingSupplierParty">
        <cac:AccountingSupplierParty>
            <cac:Party>
                <cbc:EndpointID schemeID="0088">
                    <xsl:value-of select="cac:Party/cbc:EndpointID"/>
                </cbc:EndpointID>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="0184">
                        <xsl:value-of select="cac:Party/cac:PartyIdentification/cbc:ID"/>
                    </cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name>
                        <xsl:value-of select="cac:Party/cac:PartyName/cbc:Name"/>
                    </cbc:Name>
                </cac:PartyName>
                <cac:PostalAddress>
                    <cbc:StreetName>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:StreetName"/>
                    </cbc:StreetName>
                    <cbc:BuildingNumber>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:BuildingNumber"/>
                    </cbc:BuildingNumber>
                    <cbc:CityName>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:CityName"/>
                    </cbc:CityName>
                    <cbc:PostalZone>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:PostalZone"/>
                    </cbc:PostalZone>
                    <cbc:CountrySubentity>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:CountrySubentity"/>
                    </cbc:CountrySubentity>
                    <cac:Country>
                        <cbc:IdentificationCode>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cac:Country/cbc:IdentificationCode"/>
                        </cbc:IdentificationCode>
                    </cac:Country>
                </cac:PostalAddress>
                <cac:PartyTaxScheme>
                    <cbc:CompanyID>
                        <xsl:value-of select="cac:Party/cac:PartyTaxScheme/cbc:CompanyID"/>
                    </cbc:CompanyID>
                    <cac:TaxScheme>
                        <cbc:ID>
                            <xsl:value-of select="cac:Party/cac:PartyTaxScheme/cac:TaxScheme/cbc:ID"/>
                        </cbc:ID>
                    </cac:TaxScheme>
                </cac:PartyTaxScheme>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName>
                        <xsl:value-of select="cac:Party/cac:PartyLegalEntity/cbc:RegistrationName"/>
                    </cbc:RegistrationName>
                    <cbc:CompanyID>
                        <xsl:value-of select="cac:Party/cac:PartyLegalEntity/cbc:CompanyID"/>
                    </cbc:CompanyID>
                    <cac:CorporateRegistrationScheme>
                        <cbc:ID>
                            <xsl:value-of select="cac:Party/cac:PartyLegalEntity/cac:CorporateRegistrationScheme/cbc:ID"/>
                        </cbc:ID>
                    </cac:CorporateRegistrationScheme>
                </cac:PartyLegalEntity>
                <cac:Contact>
                    <cbc:Name>
                        <xsl:value-of select="cac:Party/cac:Contact/cbc:Name"/>
                    </cbc:Name>
                    <cbc:Telephone>
                        <xsl:value-of select="cac:Party/cac:Contact/cbc:Telephone"/>
                    </cbc:Telephone>
                    <cbc:ElectronicMail>
                        <xsl:value-of select="cac:Party/cac:Contact/cbc:ElectronicMail"/>
                    </cbc:ElectronicMail>
                </cac:Contact>
            </cac:Party>
        </cac:AccountingSupplierParty>
    </xsl:template>

    <!-- Template for AccountingCustomerParty -->
    <xsl:template match="cac:AccountingCustomerParty">
        <cac:AccountingCustomerParty>
            <cac:Party>
                <cbc:EndpointID schemeID="0088">
                    <xsl:value-of select="cac:Party/cbc:EndpointID"/>
                </cbc:EndpointID>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="0184">
                        <xsl:value-of select="cac:Party/cac:PartyIdentification/cbc:ID"/>
                    </cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name>
                        <xsl:value-of select="cac:Party/cac:PartyName/cbc:Name"/>
                    </cbc:Name>
                </cac:PartyName>
                <cac:PostalAddress>
                    <cbc:StreetName>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:StreetName"/>
                    </cbc:StreetName>
                    <cbc:BuildingNumber>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:BuildingNumber"/>
                    </cbc:BuildingNumber>
                    <cbc:CityName>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:CityName"/>
                    </cbc:CityName>
                    <cbc:PostalZone>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:PostalZone"/>
                    </cbc:PostalZone>
                    <cbc:CountrySubentity>
                        <xsl:value-of select="cac:Party/cac:PostalAddress/cbc:CountrySubentity"/>
                    </cbc:CountrySubentity>
                    <cac:Country>
                        <cbc:IdentificationCode>
                            <xsl:value-of select="cac:Party/cac:PostalAddress/cac:Country/cbc:IdentificationCode"/>
                        </cbc:IdentificationCode>
                    </cac:Country>
                </cac:PostalAddress>
                <cac:PartyTaxScheme>
                    <cbc:CompanyID>
                        <xsl:value-of select="cac:Party/cac:PartyTaxScheme/cbc:CompanyID"/>
                    </cbc:CompanyID>
                    <cac:TaxScheme>
                        <cbc:ID>
                            <xsl:value-of select="cac:Party/cac:PartyTaxScheme/cac:TaxScheme/cbc:ID"/>
                        </cbc:ID>
                    </cac:TaxScheme>
                </cac:PartyTaxScheme>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName>
                        <xsl:value-of select="cac:Party/cac:PartyLegalEntity/cbc:RegistrationName"/>
                    </cbc:RegistrationName>
                    <cbc:CompanyID>
                        <xsl:value-of select="cac:Party/cac:PartyLegalEntity/cbc:CompanyID"/>
                    </cbc:CompanyID>
                    <cac:CorporateRegistrationScheme>
                        <cbc:ID>
                            <xsl:value-of select="cac:Party/cac:PartyLegalEntity/cac:CorporateRegistrationScheme/cbc:ID"/>
                        </cbc:ID>
                    </cac:CorporateRegistrationScheme>
                </cac:PartyLegalEntity>
                <cac:Contact>
                    <cbc:Name>
                        <xsl:value-of select="cac:Party/cac:Contact/cbc:Name"/>
                    </cbc:Name>
                    <cbc:Telephone>
                        <xsl:value-of select="cac:Party/cac:Contact/cbc:Telephone"/>
                    </cbc:Telephone>
                    <cbc:ElectronicMail>
                        <xsl:value-of select="cac:Party/cac:Contact/cbc:ElectronicMail"/>
                    </cbc:ElectronicMail>
                </cac:Contact>
            </cac:Party>
        </cac:AccountingCustomerParty>
    </xsl:template>

    <!-- Template for InvoiceLine -->
    <xsl:template match="cac:InvoiceLine">
        <cac:InvoiceLine>
            <cbc:ID>
                <xsl:value-of select="cbc:ID"/>
            </cbc:ID>
            <cbc:InvoicedQuantity unitCode="C62">
                <xsl:value-of select="cbc:InvoicedQuantity"/>
            </cbc:InvoicedQuantity>
            <cbc:LineExtensionAmount currencyID="DKK">
                <xsl:value-of select="cbc:LineExtensionAmount"/>
            </cbc:LineExtensionAmount>
            <cac:Item>
                <cbc:Name>
                    <xsl:value-of select="cac:Item/cbc:Name"/>
                </cbc:Name>
                <cac:ClassifiedTaxCategory>
                    <cbc:ID>
                        <xsl:value-of select="cac:Item/cac:ClassifiedTaxCategory/cbc:ID"/>
                    </cbc:ID>
                    <cbc:Percent>
                        <xsl:value-of select="cac:Item/cac:ClassifiedTaxCategory/cbc:Percent"/>
                    </cbc:Percent>
                    <cac:TaxScheme>
                        <cbc:ID>
                            <xsl:value-of select="cac:Item/cac:ClassifiedTaxCategory/cac:TaxScheme/cbc:ID"/>
                        </cbc:ID>
                    </cac:TaxScheme>
                </cac:ClassifiedTaxCategory>
            </cac:Item>
            <cac:Price>
                <cbc:PriceAmount currencyID="DKK">
                    <xsl:value-of select="cac:Price/cbc:PriceAmount"/>
                </cbc:PriceAmount>
            </cac:Price>
        </cac:InvoiceLine>
    </xsl:template>

</xsl:stylesheet>
