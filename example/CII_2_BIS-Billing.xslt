<?xml version="1.0" encoding="UTF-8"?>
<!--
******************************************************************************************************************

	Conversion from CII to PEPPOL BIS Billing.

	Publisher:          NemHandel / Erhvervsstyrelsen

	Description:		General conversion of the CII invoice and credit note syntax, to the PEPPOL BIS Billing syntax.
	Rights:				It can be used following the Common Creative License

	all terms derived from http://dublincore.org/documents/dcmi-terms/
	For more information, see www.nemhandel.dk

	Changed:20191115: Updated after comments in the Peppol Fall release - change to EndpointID for Supplier.
	Changed:20191204: AccountingCustomerParty EndpointID mapped from ram:URIUniversalCommunication/ram:URIID

******************************************************************************************************************
-->
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100" xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:100" xmlns:rsm="urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100" xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100" exclude-result-prefixes="xs rsm ram qdt udt">
	<xsl:output method="xml" encoding="UTF-8" indent="yes"/>
	<xsl:strip-space elements="*"/>
	<xsl:template match="/">
		<xsl:apply-templates/>
	</xsl:template>
	<xsl:template match="*">
		<Error>
			<Errortext>Fatal error: Unsupported documenttype! This stylesheet only supports conversion of CII Invoice or CreditNote</Errortext>
			<Input>
				<xsl:value-of select="."/>
			</Input>
		</Error>
	</xsl:template>
	<!--Global Variables-->
	<xsl:variable name="TotalTaxAmount" select="rsm:CrossIndustryInvoice/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount"/>
	<xsl:variable name="TotalTaxAmountCurrency" select="rsm:CrossIndustryInvoice/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount[1]/@currencyID"/>
	<xsl:variable name="HeaderCurrency" select="rsm:CrossIndustryInvoice/rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceCurrencyCode"/>
	<!--Root Match of CII Invoice or CreditNote-->
	<xsl:template match="/rsm:CrossIndustryInvoice">
		<!--Decision to create PEPPOL BIS Invoice or CreditNote-->
		<xsl:choose>
			<xsl:when test="rsm:ExchangedDocument/ram:TypeCode = ('80','82','84','130','202','203','204','211','295','325','326','380','383','384','385','386','387','388','389','390','393','394','395','456','457','527','575','623','633','751','780','935')">
				<xsl:call-template name="InvoiceMapping"/>
			</xsl:when>
			<xsl:when test="rsm:ExchangedDocument/ram:TypeCode = ('81','83','261','262','295','308','381','396','420','458')">
				<xsl:call-template name="CreditNoteMapping"/>
			</xsl:when>
		</xsl:choose>
	</xsl:template>
	<!--Template that create the PEPPOL BIS v3 Invoice-->
	<xsl:template name="InvoiceMapping">
		<!--Variables for Invoice header-->
		<xsl:variable name="CustomizationID" select="'urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0'"/>
		<xsl:variable name="ProfileID" select="'urn:fdc:peppol.eu:2017:poacc:billing:01:1.0'"/>
		<xsl:variable name="InvoiceNumber" select="rsm:ExchangedDocument/ram:ID"/>
		<xsl:variable name="IssueDate">
			<xsl:value-of select="substring(rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString, 7,2)"/>
		</xsl:variable>
		<xsl:variable name="DueDate">
			<xsl:value-of select="substring(rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:DueDateDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:DueDateDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms/ram:DueDateDateTime/udt:DateTimeString, 7,2)"/>
		</xsl:variable>
		<xsl:variable name="InvoiceTypeCode">
			<xsl:choose>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='130'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='202'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='203'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='204'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='211'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='295'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='325'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='326'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='384'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='385'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='387'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='389'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='390'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='394'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='395'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='456'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='457'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='527'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='633'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='751'">380</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='935'">380</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="rsm:ExchangedDocument/ram:TypeCode"/>
				</xsl:otherwise> 
			</xsl:choose>
		</xsl:variable>
		<xsl:variable name="HeaderNote" select="rsm:ExchangedDocument/ram:IncludedNote/ram:Content"/>
		<xsl:variable name="TaxPointDate">
			<xsl:value-of select="substring(rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TaxPointDate/udt:DateString, 1,4)"/>-<xsl:value-of select="substring(rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TaxPointDate/udt:DateString, 5,2)"/>-<xsl:value-of select="substring(rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TaxPointDate/udt:DateString, 7,2)"/>
		</xsl:variable>
		<xsl:variable name="CurrencyCode" select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceCurrencyCode"/>
		<xsl:variable name="TaxCurrencyCode" select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:TaxCurrencyCode"/>
		<xsl:variable name="AccountingCost" select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID"/>
		<xsl:variable name="BuyerReference" select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerReference"/>
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
			<cbc:InvoiceTypeCode>
				<xsl:value-of select="$InvoiceTypeCode"/>
			</cbc:InvoiceTypeCode>
			<xsl:apply-templates select="rsm:ExchangedDocument/ram:IncludedNote"/>
			<xsl:if test="string(cbc:TaxPointDate)">
				<cbc:TaxPointDate>
					<xsl:value-of select="cbc:TaxPointDate"/>
				</cbc:TaxPointDate>
			</xsl:if>
			<cbc:DocumentCurrencyCode>
				<xsl:value-of select="$CurrencyCode"/>
			</cbc:DocumentCurrencyCode>
			<xsl:if test="$TaxCurrencyCode !='' and $TaxCurrencyCode != $CurrencyCode">
				<cbc:TaxCurrencyCode>
					<xsl:value-of select="$TaxCurrencyCode"/>
				</cbc:TaxCurrencyCode>
			</xsl:if>
			<xsl:if test="string($AccountingCost)">
				<cbc:AccountingCost>
					<xsl:value-of select="$AccountingCost"/>
				</cbc:AccountingCost>
			</xsl:if>
			<xsl:if test="string($BuyerReference)">
				<cbc:BuyerReference>
					<xsl:value-of select="$BuyerReference"/>
				</cbc:BuyerReference>
			</xsl:if>
			<!--Inserterting InvoicePeriod if present-->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:BillingSpecifiedPeriod"/>
			<!-- OrderReference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument"/>
			<!-- Billing Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceReferencedDocument"/>
			<!-- Despatch document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:DespatchAdviceReferencedDocument"/>
			<!-- Receipt document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ReceivingAdviceReferencedDocument"/>
			<!-- Originator document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement[ram:AdditionalReferencedDocument/ram:TypeCode = '50']"/>
			<!-- Contract document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument"/>
			<!-- Additional document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument[ram:TypeCode != '50']"/>
			<!-- Project Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject"/>
			<!-- AccountingSupplierParty -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- AccountingCustomerParty -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- PayeeParty -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:PayeeTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- TaxRepresentative Party -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTaxRepresentativeTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- Delivery Header Party -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- Payment Means Party -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans">
		</xsl:apply-templates>
			<!-- PaymentTerms -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms">
		</xsl:apply-templates>
			<!-- AllowanceCharge -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge">
		</xsl:apply-templates>
			<!-- TaxTotal and total tax amount -->
			<!--Is only created if there is at least one ram:ApplicableTradeTax in CII source and total tax amount is created if present in the CII source-->
			<xsl:if test="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax">
				<cac:TaxTotal>
					<xsl:if test="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount">
						<cbc:TaxAmount>
							<xsl:choose>
								<xsl:when test="string($TotalTaxAmountCurrency)">
									<xsl:attribute name="currencyID" select="$TotalTaxAmountCurrency"/>
								</xsl:when>
								<xsl:otherwise>
									<xsl:attribute name="currencyID" select="$HeaderCurrency"/>
								</xsl:otherwise>
							</xsl:choose>
							<xsl:value-of select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount[@currencyID=../../../ram:ApplicableHeaderTradeSettlement/ram:InvoiceCurrencyCode]"/>
						</cbc:TaxAmount>
					</xsl:if>
					<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax"/>
				</cac:TaxTotal>
			</xsl:if>
			<!--If there is a TaxCurrency and  a Invoice total VAT amount in accounting currency a taxtotal with this amount is created-->
			<xsl:if test="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount[@currencyID=../../../ram:ApplicableHeaderTradeSettlement/ram:TaxCurrencyCode]">
				<cac:TaxTotal>
					<cbc:TaxAmount currencyID="{$TaxCurrencyCode}">
						<xsl:value-of select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount[@currencyID=../../../ram:ApplicableHeaderTradeSettlement/ram:TaxCurrencyCode]"/>
					</cbc:TaxAmount>
				</cac:TaxTotal>
			</xsl:if>
			<!--Totals-->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation"/>
			<!--InvoiceLines-->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem"/>
		</Invoice>
	</xsl:template>
	<!-- ............................................................ -->
	<!--           Templates for Invoice start						  -->
	<!-- ............................................................ -->
	<!--Note template-->
	<xsl:template match="rsm:ExchangedDocument/ram:IncludedNote">
		<cbc:Note>
			<xsl:value-of select="ram:Content"/>
		</cbc:Note>
	</xsl:template>
	<!--Invoice Period-->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:BillingSpecifiedPeriod">
		<cac:InvoicePeriod>
			<cbc:StartDate>
				<xsl:value-of select="substring(ram:StartDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(ram:StartDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(ram:StartDateTime/udt:DateTimeString, 7,2)"/>
			</cbc:StartDate>
			<cbc:EndDate>
				<xsl:value-of select="substring(ram:EndDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(ram:EndDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(ram:EndDateTime/udt:DateTimeString, 7,2)"/>
			</cbc:EndDate>
		</cac:InvoicePeriod>
	</xsl:template>
	<!--OrderReference-->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument">
		<cac:OrderReference>
			<cbc:ID>
				<xsl:value-of select="ram:IssuerAssignedID"/>
			</cbc:ID>
			<xsl:if test="../ram:SellerOrderReferencedDocument/ram:IssuerAssignedID">
				<cbc:SalesOrderID>
					<xsl:value-of select="../ram:SellerOrderReferencedDocument/ram:IssuerAssignedID"/>
				</cbc:SalesOrderID>
			</xsl:if>
		</cac:OrderReference>
	</xsl:template>
	<!--Billing Reference-->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceReferencedDocument">
		<cac:BillingReference>
			<cac:InvoiceDocumentReference>
				<cbc:ID>
					<xsl:value-of select="ram:IssuerAssignedID"/>
				</cbc:ID>
				<xsl:if test="ram:FormattedIssueDateTime/qdt:DateTimeString">
					<cbc:IssueDate>
						<xsl:value-of select="substring(ram:FormattedIssueDateTime/qdt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(ram:FormattedIssueDateTime/qdt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(ram:FormattedIssueDateTime/qdt:DateTimeString, 7,2)"/>
					</cbc:IssueDate>
				</xsl:if>
			</cac:InvoiceDocumentReference>
		</cac:BillingReference>
	</xsl:template>
	<!--Despatch document Reference-->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:DespatchAdviceReferencedDocument">
		<cac:DespatchDocumentReference>
			<cbc:ID>
				<xsl:value-of select="ram:IssuerAssignedID"/>
			</cbc:ID>
		</cac:DespatchDocumentReference>
	</xsl:template>
	<!--Receipt document Reference-->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ReceivingAdviceReferencedDocument">
		<cac:ReceiptDocumentReference>
			<cbc:ID>
				<xsl:value-of select="ram:IssuerAssignedID"/>
			</cbc:ID>
		</cac:ReceiptDocumentReference>
	</xsl:template>
	<!--Originator document Reference-->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement[ram:AdditionalReferencedDocument/ram:TypeCode = '50']">
		<cac:OriginatorDocumentReference>
			<cbc:ID>
				<xsl:value-of select="ram:AdditionalReferencedDocument[ram:TypeCode = '50']/ram:IssuerAssignedID"/>
			</cbc:ID>
		</cac:OriginatorDocumentReference>
	</xsl:template>
	<!--Contract document Reference-->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument">
		<cac:ContractDocumentReference>
			<cbc:ID>
				<xsl:value-of select="ram:IssuerAssignedID"/>
			</cbc:ID>
		</cac:ContractDocumentReference>
	</xsl:template>
	<!--Additional document Reference-->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument[ram:TypeCode != '50']">
		<xsl:if test="../ram:AdditionalReferencedDocument[ram:TypeCode != '50']">
			<xsl:variable name="MimeCode" select="ram:AttachmentBinaryObject/@mimeCode"/>
			<xsl:variable name="fileName" select="ram:AttachmentBinaryObject/@filename"/>
			<xsl:variable name="Type" select="ram:TypeCode"/>
			<cac:AdditionalDocumentReference>
				<cbc:ID>
					<xsl:value-of select="ram:IssuerAssignedID"/>
				</cbc:ID>
				<xsl:if test="$Type">
					<cbc:DocumentTypeCode>
						<xsl:value-of select="$Type"/>
					</cbc:DocumentTypeCode>
				</xsl:if>
				<xsl:if test="string(ram:Name)">
					<cbc:DocumentDescription>
						<xsl:value-of select="ram:Name"/>
					</cbc:DocumentDescription>
				</xsl:if>
				<xsl:if test="ram:AttachmentBinaryObject">
					<cac:Attachment>
						<cbc:EmbeddedDocumentBinaryObject mimeCode="{$MimeCode}" filename="{$fileName}">
							<xsl:value-of select="ram:AttachmentBinaryObject"/>
						</cbc:EmbeddedDocumentBinaryObject>
						<xsl:if test="string(ram:URIID)">
							<cac:ExternalReference>
								<cbc:URI>
									<xsl:value-of select="ram:URIID"/>
								</cbc:URI>
							</cac:ExternalReference>
						</xsl:if>
					</cac:Attachment>
				</xsl:if>
			</cac:AdditionalDocumentReference>
		</xsl:if>
	</xsl:template>
	<!--Project Reference -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SpecifiedProcuringProject">
		<cac:ProjectReference>
			<cbc:ID>
				<xsl:value-of select="ram:ID"/>
			</cbc:ID>
		</cac:ProjectReference>
	</xsl:template>
	<!--Accounting Supplier Party main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty">
		<!--Variables-->
		<xsl:variable name="SchemeIDGlobal" select="ram:GlobalID/@schemeID"/>
		<xsl:variable name="SchemeIDGlobalValue" select="ram:GlobalID"/>
		<xsl:variable name="SchemeIDUURID" select="ram:URIUniversalCommunication/ram:URIID/@schemeID"/>
		<xsl:variable name="SchemeIDUURIDValue" select="ram:URIUniversalCommunication/ram:URIID"/>
		<xsl:variable name="SchemIDCOmpanyIDLegalEntity" select="ram:SpecifiedLegalOrganization/ram:ID/@schemeID"/>
		<!--Varibles for the supplier class-->
		<cac:AccountingSupplierParty>
			<cac:Party>
				<!-- if URIID is found on seller endpointid is mapped-->
				<xsl:if test="string($SchemeIDUURIDValue)">
					<xsl:call-template name="EndpointIDSchemeIDmapping">
						<xsl:with-param name="SchemeIDUURID" select="$SchemeIDUURID"/>
						<xsl:with-param name="SchemeIDUURIDValue" select="$SchemeIDUURIDValue"/>
					</xsl:call-template>
				</xsl:if>
				<!--If GlobalID for the Seller is found the PartyIdentification class is created on behalf of that otherwise on the ram:ID of the seller owtherwise nothing-->
				<xsl:choose>
					<xsl:when test="string($SchemeIDGlobalValue)">
						<xsl:call-template name="PartyIDSchemeIDmapping">
							<xsl:with-param name="GlobalIDScheme" select="$SchemeIDGlobal"/>
							<xsl:with-param name="GlobalIDSchemeValue" select="$SchemeIDGlobalValue"/>
						</xsl:call-template>
					</xsl:when>
					<xsl:when test="string(ram:ID)">
						<cac:PartyIdentification>
							<cbc:ID>
								<xsl:value-of select="ram:ID"/>
							</cbc:ID>
						</cac:PartyIdentification>
					</xsl:when>
				</xsl:choose>
				<xsl:if test="../../ram:ApplicableHeaderTradeSettlement/ram:CreditorReferenceID">
					<cac:PartyIdentification>
						<cbc:ID schemeID="SEPA">
							<xsl:value-of select="../../ram:ApplicableHeaderTradeSettlement/ram:CreditorReferenceID"/>
						</cbc:ID>
					</cac:PartyIdentification>
				</xsl:if>
				<!--If CII seller PartyName is present it is mapped to BIS PArtyname-->
				<xsl:if test="string(ram:Name)">
					<cac:PartyName>
						<cbc:Name>
							<xsl:value-of select="ram:Name"/>
						</cbc:Name>
					</cac:PartyName>
				</xsl:if>
				<!--Seller postal address is always created because country code is mandatory-->
				<cac:PostalAddress>
					<xsl:if test="string(ram:PostalTradeAddress/ram:LineOne)">
						<cbc:StreetName>
							<xsl:value-of select="ram:PostalTradeAddress/ram:LineOne"/>
						</cbc:StreetName>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:LineTwo)">
						<cbc:AdditionalStreetName>
							<xsl:value-of select="ram:PostalTradeAddress/ram:LineTwo"/>
						</cbc:AdditionalStreetName>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:CityName)">
						<cbc:CityName>
							<xsl:value-of select="ram:PostalTradeAddress/ram:CityName"/>
						</cbc:CityName>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:PostcodeCode)">
						<cbc:PostalZone>
							<xsl:value-of select="ram:PostalTradeAddress/ram:PostcodeCode"/>
						</cbc:PostalZone>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:CountrySubDivisionName)">
						<cbc:CountrySubentity>
							<xsl:value-of select="ram:PostalTradeAddress/ram:CountrySubDivisionName"/>
						</cbc:CountrySubentity>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:LineThree)">
						<cac:AddressLine>
							<cbc:Line>
								<xsl:value-of select="ram:PostalTradeAddress/ram:LineThree"/>
							</cbc:Line>
						</cac:AddressLine>
					</xsl:if>
					<!--Mandatory is address class-->
					<cac:Country>
						<cbc:IdentificationCode>
							<xsl:value-of select="ram:PostalTradeAddress/ram:CountryID"/>
						</cbc:IdentificationCode>
					</cac:Country>
				</cac:PostalAddress>
				<xsl:if test="ram:SpecifiedTaxRegistration/ram:ID[@schemeID='VA']">
					<cac:PartyTaxScheme>
						<cbc:CompanyID>
							<xsl:value-of select="ram:SpecifiedTaxRegistration/ram:ID[@schemeID='VA']"/>
						</cbc:CompanyID>
						<cac:TaxScheme>
							<cbc:ID>VAT</cbc:ID>
						</cac:TaxScheme>
					</cac:PartyTaxScheme>
				</xsl:if>
				<xsl:if test="ram:SpecifiedTaxRegistration/ram:ID[@schemeID !='VA']">
					<cac:PartyTaxScheme>
						<cbc:CompanyID>
							<xsl:value-of select="ram:SpecifiedTaxRegistration/ram:ID[@schemeID !='VA']"/>
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
							<xsl:when test="ram:SpecifiedLegalOrganization/ram:TradingBusinessName">
								<xsl:value-of select="ram:SpecifiedLegalOrganization/ram:TradingBusinessName"/>
							</xsl:when>
							<xsl:when test="ram:Name">
								<xsl:value-of select="ram:Name"/>
							</xsl:when>
						</xsl:choose>
					</cbc:RegistrationName>
					<xsl:if test="string(ram:SpecifiedLegalOrganization/ram:ID)">
						<cbc:CompanyID>
							<xsl:if test="string(ram:SpecifiedLegalOrganization/ram:ID/@schemeID)">
								<xsl:attribute name="schemeID"><xsl:value-of select="ram:SpecifiedLegalOrganization/ram:ID/@schemeID"/></xsl:attribute>
							</xsl:if>
							<xsl:value-of select="ram:SpecifiedLegalOrganization/ram:ID"/>
						</cbc:CompanyID>
						<xsl:if test="ram:Description">
							<cbc:CompanyLegalForm>
								<xsl:value-of select="ram:Description"/>
							</cbc:CompanyLegalForm>
						</xsl:if>
					</xsl:if>
				</cac:PartyLegalEntity>
				<!--Contact class will be created if the defined trad contact is present in the CII Invoice-->
				<xsl:if test="ram:DefinedTradeContact">
					<cac:Contact>
						<xsl:if test="ram:DefinedTradeContact/ram:PersonName">
							<cbc:Name>
								<xsl:value-of select="ram:DefinedTradeContact/ram:PersonName"/>
							</cbc:Name>
						</xsl:if>
						<xsl:if test="ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber">
							<cbc:Telephone>
								<xsl:value-of select="ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber"/>
							</cbc:Telephone>
						</xsl:if>
						<xsl:if test="ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID">
							<cbc:ElectronicMail>
								<xsl:value-of select="ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID"/>
							</cbc:ElectronicMail>
						</xsl:if>
					</cac:Contact>
				</xsl:if>
			</cac:Party>
		</cac:AccountingSupplierParty>
	</xsl:template>
	<!--Accounting Customer Party main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty">
		<!--Variables-->
		<xsl:variable name="SchemeIDGlobal" select="ram:GlobalID/@schemeID"/>
		<xsl:variable name="SchemeIDGlobalValue" select="ram:GlobalID"/>
		<xsl:variable name="SchemeIDUURID" select="ram:URIUniversalCommunication/ram:URIID/@schemeID"/>
		<xsl:variable name="SchemeIDUURIDValue" select="ram:URIUniversalCommunication/ram:URIID"/>
		<xsl:variable name="SchemIDCOmpanyIDLegalEntity" select="ram:SpecifiedLegalOrganization/ram:ID/@schemeID"/>
		<!--Varibles for the Customer class-->
		<cac:AccountingCustomerParty>
			<cac:Party>
				<!-- if GlobalID is found on seller endpointid is mapped-->
				<xsl:if test="string($SchemeIDGlobalValue)">
					<xsl:call-template name="EndpointIDSchemeIDmapping">
						<xsl:with-param name="SchemeIDUURID" select="$SchemeIDUURID"/>
						<xsl:with-param name="SchemeIDUURIDValue" select="$SchemeIDUURIDValue"/>
					</xsl:call-template>
				</xsl:if>
				<!--If GlobalID for the Customer is found the PartyIdentification class is created on behalf of that otherwise on the ram:ID of the seller owtherwise nothing-->
				<xsl:choose>
					<xsl:when test="string($SchemeIDGlobalValue)">
						<xsl:call-template name="PartyIDSchemeIDmapping">
							<xsl:with-param name="GlobalIDScheme" select="$SchemeIDGlobal"/>
							<xsl:with-param name="GlobalIDSchemeValue" select="$SchemeIDGlobalValue"/>
						</xsl:call-template>
					</xsl:when>
					<xsl:when test="string(ram:ID)">
						<cac:PartyIdentification>
							<cbc:ID>
								<xsl:value-of select="ram:ID"/>
							</cbc:ID>
						</cac:PartyIdentification>
					</xsl:when>
				</xsl:choose>
				<!--If CII Customer PartyName is present it is mapped to BIS PArtyname-->
				<xsl:if test="string(ram:Name)">
					<cac:PartyName>
						<cbc:Name>
							<xsl:value-of select="ram:Name"/>
						</cbc:Name>
					</cac:PartyName>
				</xsl:if>
				<!--Customer postal address is always created because country code is mandatory-->
				<cac:PostalAddress>
					<xsl:if test="string(ram:PostalTradeAddress/ram:LineOne)">
						<cbc:StreetName>
							<xsl:value-of select="ram:PostalTradeAddress/ram:LineOne"/>
						</cbc:StreetName>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:LineTwo)">
						<cbc:AdditionalStreetName>
							<xsl:value-of select="ram:PostalTradeAddress/ram:LineTwo"/>
						</cbc:AdditionalStreetName>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:CityName)">
						<cbc:CityName>
							<xsl:value-of select="ram:PostalTradeAddress/ram:CityName"/>
						</cbc:CityName>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:PostcodeCode)">
						<cbc:PostalZone>
							<xsl:value-of select="ram:PostalTradeAddress/ram:PostcodeCode"/>
						</cbc:PostalZone>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:CountrySubDivisionName)">
						<cbc:CountrySubentity>
							<xsl:value-of select="ram:PostalTradeAddress/ram:CountrySubDivisionName"/>
						</cbc:CountrySubentity>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:LineThree)">
						<cac:AddressLine>
							<cbc:Line>
								<xsl:value-of select="ram:PostalTradeAddress/ram:LineThree"/>
							</cbc:Line>
						</cac:AddressLine>
					</xsl:if>
					<!--Mandatory is address class-->
					<cac:Country>
						<cbc:IdentificationCode>
							<xsl:value-of select="ram:PostalTradeAddress/ram:CountryID"/>
						</cbc:IdentificationCode>
					</cac:Country>
				</cac:PostalAddress>
				<!-- PartyLegalEntity will always be present for the Customer because the company registration name is mandatory-->
				<cac:PartyLegalEntity>
					<cbc:RegistrationName>
						<xsl:choose>
							<xsl:when test="ram:SpecifiedLegalOrganization/ram:TradingBusinessName">
								<xsl:value-of select="ram:SpecifiedLegalOrganization/ram:TradingBusinessName"/>
							</xsl:when>
							<xsl:when test="ram:Name">
								<xsl:value-of select="ram:Name"/>
							</xsl:when>
						</xsl:choose>
					</cbc:RegistrationName>
					<xsl:if test="string(ram:SpecifiedLegalOrganization/ram:ID)">
						<cbc:CompanyID>
							<xsl:if test="string(ram:SpecifiedLegalOrganization/ram:ID/@schemeID)">
								<xsl:attribute name="schemeID"><xsl:value-of select="ram:SpecifiedLegalOrganization/ram:ID/@schemeID"/></xsl:attribute>
							</xsl:if>
							<xsl:value-of select="ram:SpecifiedLegalOrganization/ram:ID"/>
						</cbc:CompanyID>
					</xsl:if>
					<xsl:if test="ram:Description">
						<cbc:CompanyLegalForm>
							<xsl:value-of select="ram:Description"/>
						</cbc:CompanyLegalForm>
					</xsl:if>
				</cac:PartyLegalEntity>
				<!--Contact class will be created if the defined trad contact if present in the CII Invoice-->
				<xsl:if test="ram:DefinedTradeContact">
					<cac:Contact>
						<xsl:if test="ram:DefinedTradeContact/ram:PersonName">
							<cbc:Name>
								<xsl:value-of select="ram:DefinedTradeContact/ram:PersonName"/>
							</cbc:Name>
						</xsl:if>
						<xsl:if test="ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber">
							<cbc:Telephone>
								<xsl:value-of select="ram:DefinedTradeContact/ram:TelephoneUniversalCommunication/ram:CompleteNumber"/>
							</cbc:Telephone>
						</xsl:if>
						<xsl:if test="ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID">
							<cbc:ElectronicMail>
								<xsl:value-of select="ram:DefinedTradeContact/ram:EmailURIUniversalCommunication/ram:URIID"/>
							</cbc:ElectronicMail>
						</xsl:if>
					</cac:Contact>
				</xsl:if>
			</cac:Party>
		</cac:AccountingCustomerParty>
	</xsl:template>
	<!--Payee Party main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:PayeeTradeParty">
		<!--Variables-->
		<xsl:variable name="SchemeIDGlobal" select="ram:GlobalID/@schemeID"/>
		<xsl:variable name="SchemeIDGlobalValue" select="ram:GlobalID"/>
		<!--Varibles for the Payee class-->
		<cac:PayeeParty>
			<!--If ram:ID of the seller present owtherwise nothing-->
			<xsl:if test="string(ram:ID)">
				<cac:PartyIdentification>
					<cbc:ID>
						<xsl:value-of select="ram:ID"/>
					</cbc:ID>
				</cac:PartyIdentification>
			</xsl:if>
			<!--Mandatory in PEPPOL BIS-->
			<cac:PartyName>
				<cbc:Name>
					<xsl:value-of select="ram:Name"/>
				</cbc:Name>
			</cac:PartyName>
			<!-- PartyLegalEntity will always be present for the supplier because the company registration name is mandatory-->
			<xsl:if test="string(ram:SpecifiedLegalOrganization/ram:ID)">
				<cac:PartyLegalEntity>
					<cbc:CompanyID>
						<xsl:if test="string(ram:SpecifiedLegalOrganization/ram:ID/@schemeID)">
							<xsl:attribute name="schemeID"><xsl:value-of select="ram:SpecifiedLegalOrganization/ram:ID/@schemeID"/></xsl:attribute>
						</xsl:if>
						<xsl:value-of select="ram:SpecifiedLegalOrganization/ram:ID"/>
					</cbc:CompanyID>
				</cac:PartyLegalEntity>
			</xsl:if>
		</cac:PayeeParty>
	</xsl:template>
	<!--Tax Representative Party main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTaxRepresentativeTradeParty">
		<!--Variables-->
		<xsl:variable name="SchemeIDGlobal" select="ram:GlobalID/@schemeID"/>
		<xsl:variable name="SchemeIDGlobalValue" select="ram:GlobalID"/>
		<xsl:variable name="SchemIDCOmpanyIDLegalEntity" select="ram:SpecifiedLegalOrganization/ram:ID/@schemeID"/>
		<!--Varibles for the supplier class-->
		<cac:TaxRepresentativeParty>
			<!-- if GlobalID is found on seller endpointid is mapped-->
			<xsl:if test="string($SchemeIDGlobalValue)">
				<xsl:call-template name="EndpointIDSchemeIDmapping">
					<xsl:with-param name="SchemeIDUURID" select="$SchemeIDGlobal"/>
					<xsl:with-param name="SchemeIDUURIDValue" select="$SchemeIDGlobalValue"/>
				</xsl:call-template>
			</xsl:if>
			<!--If GlobalID for the Seller is found the PartyIdentification class is created on behalf of that otherwise on the ram:ID of the seller owtherwise nothing-->
			<xsl:choose>
				<xsl:when test="string($SchemeIDGlobalValue)">
					<xsl:call-template name="PartyIDSchemeIDmapping">
						<xsl:with-param name="GlobalIDScheme" select="$SchemeIDGlobal"/>
						<xsl:with-param name="GlobalIDSchemeValue" select="$SchemeIDGlobalValue"/>
					</xsl:call-template>
				</xsl:when>
				<xsl:when test="string(ram:ID)">
					<cac:PartyIdentification>
						<cbc:ID>
							<xsl:value-of select="ram:ID"/>
						</cbc:ID>
					</cac:PartyIdentification>
				</xsl:when>
			</xsl:choose>
			<!--If CII seller PartyName is present it is mapped to BIS PArtyname-->
			<xsl:if test="string(ram:Name)">
				<cac:PartyName>
					<cbc:Name>
						<xsl:value-of select="ram:Name"/>
					</cbc:Name>
				</cac:PartyName>
			</xsl:if>
			<!--Seller postal address is always created because country code is mandatory-->
			<cac:PostalAddress>
				<xsl:if test="string(ram:PostalTradeAddress/ram:LineOne)">
					<cbc:StreetName>
						<xsl:value-of select="ram:PostalTradeAddress/ram:LineOne"/>
					</cbc:StreetName>
				</xsl:if>
				<xsl:if test="string(ram:PostalTradeAddress/ram:LineTwo)">
					<cbc:AdditionalStreetName>
						<xsl:value-of select="ram:PostalTradeAddress/ram:LineTwo"/>
					</cbc:AdditionalStreetName>
				</xsl:if>
				<xsl:if test="string(ram:PostalTradeAddress/ram:CityName)">
					<cbc:CityName>
						<xsl:value-of select="ram:PostalTradeAddress/ram:CityName"/>
					</cbc:CityName>
				</xsl:if>
				<xsl:if test="string(ram:PostalTradeAddress/ram:PostcodeCode)">
					<cbc:PostalZone>
						<xsl:value-of select="ram:PostalTradeAddress/ram:PostcodeCode"/>
					</cbc:PostalZone>
				</xsl:if>
				<xsl:if test="string(ram:PostalTradeAddress/ram:CountrySubDivisionName)">
					<cbc:CountrySubentity>
						<xsl:value-of select="ram:PostalTradeAddress/ram:CountrySubDivisionName"/>
					</cbc:CountrySubentity>
				</xsl:if>
				<xsl:if test="string(ram:PostalTradeAddress/ram:LineThree)">
					<cac:AddressLine>
						<cbc:Line>
							<xsl:value-of select="ram:PostalTradeAddress/ram:LineThree"/>
						</cbc:Line>
					</cac:AddressLine>
				</xsl:if>
				<!--Mandatory is address class-->
				<cac:Country>
					<cbc:IdentificationCode>
						<xsl:value-of select="ram:PostalTradeAddress/ram:CountryID"/>
					</cbc:IdentificationCode>
				</cac:Country>
			</cac:PostalAddress>
			<xsl:if test="string(ram:SpecifiedTaxRegistration/ram:ID)">
				<cac:PartyTaxScheme>
					<cbc:CompanyID>
						<xsl:value-of select="ram:SpecifiedTaxRegistration/ram:ID"/>
					</cbc:CompanyID>
					<cac:TaxScheme>
						<cbc:ID>VAT</cbc:ID>
					</cac:TaxScheme>
				</cac:PartyTaxScheme>
			</xsl:if>
		</cac:TaxRepresentativeParty>
	</xsl:template>
	<!--Delivery header main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty">
		<!--Variables-->
		<xsl:variable name="SchemeIDGlobal" select="ram:GlobalID/@schemeID"/>
		<xsl:variable name="SchemeIDGlobalValue" select="ram:GlobalID"/>
		<xsl:variable name="SchemIDCOmpanyIDLegalEntity" select="ram:SpecifiedLegalOrganization/ram:ID/@schemeID"/>
		<!--Varibles for the Delivery class-->
		<cac:Delivery>
			<xsl:if test="string(ram:ActualDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString)">
				<cbc:ActualDeliveryDate>
					<xsl:value-of select="ram:ActualDeliverySupplyChainEvent/ram:OccurrenceDateTime/udt:DateTimeString"/>
				</cbc:ActualDeliveryDate>
			</xsl:if>
			<!--Delivery Address address is always created because country code is mandatory-->
			<cac:DeliveryLocation>
				<!-- if GlobalID is found on Delivery endpointid is mapped-->
				<xsl:if test="string($SchemeIDGlobalValue)">
					<xsl:call-template name="DeliverySchemeIDmapping">
						<xsl:with-param name="GlobalIDScheme" select="$SchemeIDGlobal"/>
						<xsl:with-param name="GlobalIDSchemeValue" select="$SchemeIDGlobalValue"/>
					</xsl:call-template>
				</xsl:if>
				<cac:Address>
					<xsl:if test="string(ram:PostalTradeAddress/ram:LineOne)">
						<cbc:StreetName>
							<xsl:value-of select="ram:PostalTradeAddress/ram:LineOne"/>
						</cbc:StreetName>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:LineTwo)">
						<cbc:AdditionalStreetName>
							<xsl:value-of select="ram:PostalTradeAddress/ram:LineTwo"/>
						</cbc:AdditionalStreetName>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:CityName)">
						<cbc:CityName>
							<xsl:value-of select="ram:PostalTradeAddress/ram:CityName"/>
						</cbc:CityName>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:PostcodeCode)">
						<cbc:PostalZone>
							<xsl:value-of select="ram:PostalTradeAddress/ram:PostcodeCode"/>
						</cbc:PostalZone>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:CountrySubDivisionName)">
						<cbc:CountrySubentity>
							<xsl:value-of select="ram:PostalTradeAddress/ram:CountrySubDivisionName"/>
						</cbc:CountrySubentity>
					</xsl:if>
					<xsl:if test="string(ram:PostalTradeAddress/ram:LineThree)">
						<cac:AddressLine>
							<cbc:Line>
								<xsl:value-of select="ram:PostalTradeAddress/ram:LineThree"/>
							</cbc:Line>
						</cac:AddressLine>
					</xsl:if>
					<!--Mandatory is address class-->
					<cac:Country>
						<cbc:IdentificationCode>
							<xsl:value-of select="ram:PostalTradeAddress/ram:CountryID"/>
						</cbc:IdentificationCode>
					</cac:Country>
				</cac:Address>
			</cac:DeliveryLocation>
			<xsl:if test="string(ram:Name)">
				<cac:DeliveryParty>
					<cac:PartyName>
						<cbc:Name>
							<xsl:value-of select="ram:Name"/>
						</cbc:Name>
					</cac:PartyName>
				</cac:DeliveryParty>
			</xsl:if>
		</cac:Delivery>
	</xsl:template>
	<!--Payment Means header main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans">
		<xsl:variable name="DueDate">
			<xsl:value-of select="substring(../ram:SpecifiedTradePaymentTerms/ram:DueDateDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(../ram:SpecifiedTradePaymentTerms/ram:DueDateDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(../ram:SpecifiedTradePaymentTerms/ram:DueDateDateTime/udt:DateTimeString, 7,2)"/>
		</xsl:variable>
		<cac:PaymentMeans>
			<cbc:PaymentMeansCode>
				<xsl:value-of select="ram:TypeCode"/>
			</cbc:PaymentMeansCode>
			<xsl:if test="../../../rsm:ExchangedDocument/ram:TypeCode = '381'">
				<cbc:PaymentDueDate>
					<xsl:value-of select="$DueDate"/>
				</cbc:PaymentDueDate>
			</xsl:if>
			<xsl:if test="string(../ram:PaymentReference)">
				<cbc:PaymentID>
					<xsl:value-of select="../ram:PaymentReference"/>
				</cbc:PaymentID>
			</xsl:if>
			<xsl:if test="ram:ApplicableTradeSettlementFinancialCard">
				<cac:CardAccount>
					<cbc:PrimaryAccountNumberID>
						<xsl:value-of select="ram:ApplicableTradeSettlementFinancialCard/ram:ID"/>
					</cbc:PrimaryAccountNumberID>
					<cbc:NetworkID>
						<xsl:value-of select="'N/A'"/>
					</cbc:NetworkID>
					<xsl:if test="ram:ApplicableTradeSettlementFinancialCard/ram:CardholderName">
						<cbc:HolderName>
							<xsl:value-of select="ram:ApplicableTradeSettlementFinancialCard/ram:CardholderName"/>
						</cbc:HolderName>
					</xsl:if>
				</cac:CardAccount>
			</xsl:if>
			<xsl:choose>
				<xsl:when test="ram:PayeePartyCreditorFinancialAccount">
					<cac:PayeeFinancialAccount>
						<cbc:ID>
							<xsl:choose>
								<xsl:when test="ram:PayeePartyCreditorFinancialAccount/ram:IBANID">
									<xsl:value-of select="ram:PayeePartyCreditorFinancialAccount/ram:IBANID"/>
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="ram:PayeePartyCreditorFinancialAccount/ram:ProprietaryID"/>
								</xsl:otherwise>
							</xsl:choose>
						</cbc:ID>
						<xsl:if test="ram:PayeePartyCreditorFinancialAccount/ram:AccountName">
							<cbc:Name>
								<xsl:value-of select="ram:PayeePartyCreditorFinancialAccount/ram:AccountName"/>
							</cbc:Name>
						</xsl:if>
						<xsl:if test="ram:PayeeSpecifiedCreditorFinancialInstitution/ram:BICID">
							<cac:FinancialInstitutionBranch>
								<cbc:ID>
									<xsl:value-of select="ram:PayeeSpecifiedCreditorFinancialInstitution/ram:BICID"/>
								</cbc:ID>
							</cac:FinancialInstitutionBranch>
						</xsl:if>
					</cac:PayeeFinancialAccount>
				</xsl:when>
				<xsl:when test="ram:PayerPartyDebtorFinancialAccount">
					<xsl:if test="ram:PayerSpecifiedDebtorFinancialInstitution/ram:BICID">
						<cac:PayeeFinancialAccount>
							<cac:FinancialInstitutionBranch>
								<cbc:ID>
									<xsl:value-of select="ram:PayerSpecifiedDebtorFinancialInstitution/ram:BICID"/>
								</cbc:ID>
							</cac:FinancialInstitutionBranch>
						</cac:PayeeFinancialAccount>
					</xsl:if>
					<cac:PaymentMandate>
						<xsl:if test="../ram:SpecifiedTradePaymentTerms/ram:DirectDebitMandateID">
							<cbc:ID>
								<xsl:value-of select="../ram:SpecifiedTradePaymentTerms/ram:DirectDebitMandateID"/>
							</cbc:ID>
						</xsl:if>
						<xsl:if test="ram:PayerPartyDebtorFinancialAccount/ram:IBANID">
							<cac:PayerFinancialAccount>
								<cbc:ID>
									<xsl:value-of select="ram:PayerPartyDebtorFinancialAccount/ram:IBANID"/>
								</cbc:ID>
							</cac:PayerFinancialAccount>
						</xsl:if>
					</cac:PaymentMandate>
				</xsl:when>
			</xsl:choose>
		</cac:PaymentMeans>
	</xsl:template>
	<!--Payment Terms header main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms">
		<cac:PaymentTerms>
			<cbc:Note>
				<xsl:value-of select="ram:Description"/>
			</cbc:Note>
		</cac:PaymentTerms>
	</xsl:template>
	<!--AllowanceCharge header main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge">
		<cac:AllowanceCharge>
			<!--Mandatory-->
			<cbc:ChargeIndicator>
				<xsl:value-of select="ram:ChargeIndicator/udt:Indicator"/>
			</cbc:ChargeIndicator>
			<xsl:if test="ram:ReasonCode">
				<cbc:AllowanceChargeReasonCode>
					<xsl:value-of select="ram:ReasonCode"/>
				</cbc:AllowanceChargeReasonCode>
			</xsl:if>
			<xsl:choose>
				<xsl:when test="ram:Reason">
					<cbc:AllowanceChargeReason>
						<xsl:value-of select="ram:Reason"/>
					</cbc:AllowanceChargeReason>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="'n/a'"/>
				</xsl:otherwise>
			</xsl:choose>
			<xsl:if test="ram:CalculationPercent">
				<cbc:MultiplierFactorNumeric>
					<xsl:value-of select="ram:CalculationPercent"/>
				</cbc:MultiplierFactorNumeric>
			</xsl:if>
			<!--Mandatory-->
			<cbc:Amount currencyID="{$HeaderCurrency}">
				<xsl:value-of select="format-number(ram:ActualAmount, '0.00')"/>
			</cbc:Amount>
			<xsl:if test="ram:BasisAmount">
				<cbc:BaseAmount currencyID="{$HeaderCurrency}">
					<xsl:value-of select="format-number(ram:BasisAmount, '0.00')"/>
				</cbc:BaseAmount>
			</xsl:if>
			<!--Mandatory-->
			<cac:TaxCategory>
				<cbc:ID>
					<xsl:value-of select="ram:CategoryTradeTax/ram:CategoryCode"/>
				</cbc:ID>
				<xsl:if test="ram:CategoryTradeTax/ram:RateApplicablePercent">
					<cbc:Percent>
						<xsl:value-of select="ram:CategoryTradeTax/ram:RateApplicablePercent"/>
					</cbc:Percent>
				</xsl:if>
				<cac:TaxScheme>
					<cbc:ID>VAT</cbc:ID>
				</cac:TaxScheme>
			</cac:TaxCategory>
		</cac:AllowanceCharge>
	</xsl:template>
	<!--TaxTotal header main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax">
		<cac:TaxSubtotal>
			<xsl:if test="ram:BasisAmount">
				<cbc:TaxableAmount currencyID="{$HeaderCurrency}">
					<xsl:value-of select="format-number(ram:BasisAmount, '0.00')"/>
				</cbc:TaxableAmount>
			</xsl:if>
			<!--Mandatory-->
			<cbc:TaxAmount>
				<xsl:choose>
					<xsl:when test="string($TotalTaxAmountCurrency)">
						<xsl:attribute name="currencyID" select="$TotalTaxAmountCurrency"/>
					</xsl:when>
					<xsl:otherwise>
						<xsl:attribute name="currencyID" select="$HeaderCurrency"/>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:value-of select="format-number(ram:CalculatedAmount, '0.00')"/>
			</cbc:TaxAmount>
			<xsl:if test="ram:CalculationPercent">
				<cbc:MultiplierFactorNumeric>
					<xsl:value-of select="ram:CalculationPercent"/>
				</cbc:MultiplierFactorNumeric>
			</xsl:if>
			<!--Mandatory-->
			<cac:TaxCategory>
				<cbc:ID>
					<xsl:value-of select="ram:CategoryCode"/>
				</cbc:ID>
				<xsl:if test="ram:RateApplicablePercent">
					<cbc:Percent>
						<xsl:value-of select="ram:RateApplicablePercent"/>
					</cbc:Percent>
				</xsl:if>
				<xsl:if test="ram:ExemptionReasonCode">
					<cbc:TaxExemptionReasonCode>
						<xsl:value-of select="ram:ExemptionReasonCode"/>
					</cbc:TaxExemptionReasonCode>
				</xsl:if>
				<xsl:if test="ram:ExemptionReason">
					<cbc:TaxExemptionReason>
						<xsl:value-of select="ram:ExemptionReason"/>
					</cbc:TaxExemptionReason>
				</xsl:if>
				<cac:TaxScheme>
					<cbc:ID>VAT</cbc:ID>
				</cac:TaxScheme>
			</cac:TaxCategory>
		</cac:TaxSubtotal>
	</xsl:template>
	<!--Totals header main template -->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation">
		<cac:LegalMonetaryTotal>
			<!--Mandatory-->
			<cbc:LineExtensionAmount currencyID="{$HeaderCurrency}">
				<xsl:value-of select="format-number(ram:LineTotalAmount, '0.00')"/>
			</cbc:LineExtensionAmount>
			<!--Mandatory-->
			<cbc:TaxExclusiveAmount currencyID="{$HeaderCurrency}">
				<xsl:value-of select="format-number(ram:TaxBasisTotalAmount, '0.00')"/>
			</cbc:TaxExclusiveAmount>
			<!--Mandatory-->
			<cbc:TaxInclusiveAmount currencyID="{$HeaderCurrency}">
				<xsl:value-of select="format-number(ram:GrandTotalAmount, '0.00')"/>
			</cbc:TaxInclusiveAmount>
			<xsl:if test="ram:AllowanceTotalAmount">
				<cbc:AllowanceTotalAmount currencyID="{$HeaderCurrency}">
					<xsl:value-of select="format-number(ram:AllowanceTotalAmount, '0.00')"/>
				</cbc:AllowanceTotalAmount>
			</xsl:if>
			<xsl:if test="ram:ChargeTotalAmount">
				<cbc:ChargeTotalAmount currencyID="{$HeaderCurrency}">
					<xsl:value-of select="format-number(ram:ChargeTotalAmount, '0.00')"/>
				</cbc:ChargeTotalAmount>
			</xsl:if>
			<xsl:if test="ram:TotalPrepaidAmount">
				<cbc:PrepaidAmount currencyID="{$HeaderCurrency}">
					<xsl:value-of select="format-number(ram:TotalPrepaidAmount, '0.00')"/>
				</cbc:PrepaidAmount>
			</xsl:if>
			<xsl:if test="ram:RoundingAmount">
				<cbc:PayableRoundingAmount currencyID="{$HeaderCurrency}">
					<xsl:value-of select="ram:RoundingAmount"/>
				</cbc:PayableRoundingAmount>
			</xsl:if>
			<!--Mandatory-->
			<cbc:PayableAmount currencyID="{$HeaderCurrency}">
				<xsl:value-of select="format-number(ram:DuePayableAmount, '0.00')"/>
			</cbc:PayableAmount>
		</cac:LegalMonetaryTotal>
	</xsl:template>
	<!--Invoicelines-->
	<xsl:template match="rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem">
		<!--Variables for line level info-->
		<xsl:variable name="UnitCodeScheme" select="ram:SpecifiedLineTradeDelivery/ram:BilledQuantity/@unitCode"/>
		<cac:InvoiceLine>
			<!--Mandatory-->
			<cbc:ID>
				<xsl:value-of select="ram:AssociatedDocumentLineDocument/ram:LineID"/>
			</cbc:ID>
			<xsl:if test="ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:Content">
				<cbc:Note>
					<xsl:value-of select="ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:Content"/>
				</cbc:Note>
			</xsl:if>
			<!--Mandatory-->
			<cbc:InvoicedQuantity unitCode="{$UnitCodeScheme}">
				<xsl:value-of select="ram:SpecifiedLineTradeDelivery/ram:BilledQuantity"/>
			</cbc:InvoicedQuantity>
			<!--Mandatory-->
			<cbc:LineExtensionAmount currencyID="{$HeaderCurrency}">
				<xsl:value-of select="format-number(ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeSettlementLineMonetarySummation/ram:LineTotalAmount, '0.00')"/>
			</cbc:LineExtensionAmount>
			<xsl:if test="ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID">
				<cbc:AccountingCost>
					<xsl:value-of select="ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID"/>
				</cbc:AccountingCost>
			</xsl:if>
			<!--Inserting Invoice period if present-->
			<xsl:if test="ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod">
				<cac:InvoicePeriod>
					<cbc:StartDate>
						<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString, 7,2)"/>
					</cbc:StartDate>
					<cbc:EndDate>
						<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString, 7,2)"/>
					</cbc:EndDate>
				</cac:InvoicePeriod>
			</xsl:if>
			<!--Inserting OrderReference if present-->
			<xsl:apply-templates select="ram:SpecifiedLineTradeSettlement/ram:BuyerOrderReferencedDocument"/>
			<!--Inserting Addtional document on line level if present-->
			<xsl:apply-templates select="ram:SpecifiedLineTradeSettlement/ram:AdditionalReferencedDocument"/>
			<!--Inserting Line AllowanceCharge if present-->
			<xsl:apply-templates select="ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge"/>
			<!--Inserting Item information on line level-->
			<xsl:apply-templates select="ram:SpecifiedTradeProduct"/>
			<!--Inserting Price information-->
			<xsl:apply-templates select="ram:SpecifiedLineTradeAgreement"/>
		</cac:InvoiceLine>
	</xsl:template>
	<!--Templates for Line level-->
	<!--Building Orderline if present-->
	<xsl:template match="ram:BuyerOrderReferencedDocument">
		<cac:OrderLineReference>
			<cbc:LineID>
				<xsl:value-of select="ram:LineID"/>
			</cbc:LineID>
		</cac:OrderLineReference>
	</xsl:template>
	<!--Building AdditionalReference if present-->
	<xsl:template match="ram:SpecifiedLineTradeSettlement/ram:AdditionalReferencedDocument">
		<cac:DocumentReference>
			<cbc:ID>
				<xsl:value-of select="ram:IssuerAssignedID"/>
			</cbc:ID>
			<xsl:if test="ram:TypeCode">
				<cbc:DocumentTypeCode>
					<xsl:value-of select="ram:TypeCode"/>
				</cbc:DocumentTypeCode>
			</xsl:if>
		</cac:DocumentReference>
	</xsl:template>
	<!--Building Line AllowanceCharge -->
	<xsl:template match="ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge">
		<cac:AllowanceCharge>
			<!--Mandatory-->
			<cbc:ChargeIndicator>
				<xsl:value-of select="ram:ChargeIndicator/udt:Indicator"/>
			</cbc:ChargeIndicator>
			<xsl:if test="ram:ReasonCode">
				<cbc:AllowanceChargeReasonCode>
					<xsl:value-of select="ram:ReasonCode"/>
				</cbc:AllowanceChargeReasonCode>
			</xsl:if>
			<xsl:choose>
				<xsl:when test="ram:Reason">
					<cbc:AllowanceChargeReason>
						<xsl:value-of select="ram:Reason"/>
					</cbc:AllowanceChargeReason>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="'n/a'"/>
				</xsl:otherwise>
			</xsl:choose>
			<xsl:if test="ram:CalculationPercent">
				<cbc:MultiplierFactorNumeric>
					<xsl:value-of select="ram:CalculationPercent"/>
				</cbc:MultiplierFactorNumeric>
			</xsl:if>
			<!--Mandatory-->
			<cbc:Amount currencyID="{$HeaderCurrency}">
				<xsl:value-of select="format-number(ram:ActualAmount, '0.00')"/>
			</cbc:Amount>
			<xsl:if test="ram:BasisAmount">
				<cbc:BaseAmount currencyID="{$HeaderCurrency}">
					<xsl:value-of select="format-number(ram:BasisAmount, '0.00')"/>
				</cbc:BaseAmount>
			</xsl:if>
			<!--Mandatory-->
			<!--			<cac:TaxCategory>
				<xsl:choose>
					<xsl:when test="ram:CategoryTradeTax/ram:CategoryCode">
						<cbc:ID><xsl:value-of select="ram:CategoryTradeTax/ram:CategoryCode"/></cbc:ID>	
					</xsl:when>
					<xsl:otherwise>
						<cbc:ID>
							<xsl:value-of select="../ram:ApplicableTradeTax/ram:CategoryCode"/>
						</cbc:ID>
						</xsl:otherwise>
				</xsl:choose>
				
				
				<xsl:if test="ram:CategoryTradeTax/ram:RateApplicablePercent">
					<cbc:Percent><xsl:value-of select="ram:CategoryTradeTax/ram:RateApplicablePercent"/></cbc:Percent>
				</xsl:if>
	
				<cac:TaxScheme>
					<cbc:ID>VAT</cbc:ID>
				</cac:TaxScheme>
			</cac:TaxCategory>
-->
		</cac:AllowanceCharge>
	</xsl:template>
	<!--Building Item main template -->
	<xsl:template match="ram:SpecifiedTradeProduct">
		<cac:Item>
			<xsl:if test="ram:Description">
				<cbc:Description>
					<xsl:value-of select="ram:Description"/>
				</cbc:Description>
			</xsl:if>
			<!--Mandatory Item Name-->
			<cbc:Name>
				<xsl:value-of select="ram:Name"/>
			</cbc:Name>
			<xsl:apply-templates select="ram:BuyerAssignedID"/>
			<xsl:apply-templates select="ram:SellerAssignedID"/>
			<xsl:apply-templates select="ram:GlobalID"/>
			<xsl:apply-templates select="ram:OriginTradeCountry"/>
			<xsl:apply-templates select="ram:DesignatedProductClassification"/>
			<!--Mandatory TAX on line level-->
			<xsl:apply-templates select="../ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax"/>
			<xsl:apply-templates select="ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax"/>
			<xsl:apply-templates select="ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic"/>
		</cac:Item>
	</xsl:template>
	<!--Templates for LineItem -->
	<xsl:template match="ram:BuyerAssignedID">
		<cac:BuyersItemIdentification>
			<cbc:ID>
				<xsl:value-of select="../ram:BuyerAssignedID"/>
			</cbc:ID>
		</cac:BuyersItemIdentification>
	</xsl:template>
	<xsl:template match="ram:SellerAssignedID">
		<cac:SellersItemIdentification>
			<cbc:ID>
				<xsl:value-of select="../ram:SellerAssignedID"/>
			</cbc:ID>
		</cac:SellersItemIdentification>
	</xsl:template>
	<xsl:template match="ram:GlobalID">
		<cac:StandardItemIdentification>
			<cbc:ID>
				<xsl:if test="../ram:GlobalID/@schemeID">
					<xsl:attribute name="schemeID" select="../ram:GlobalID/@schemeID"/>
				</xsl:if>
				<xsl:value-of select="../ram:GlobalID"/>
			</cbc:ID>
		</cac:StandardItemIdentification>
	</xsl:template>
	<xsl:template match="ram:OriginTradeCountry">
		<cac:OriginCountry>
			<cbc:IdentificationCode>
				<xsl:value-of select="ram:ID"/>
			</cbc:IdentificationCode>
		</cac:OriginCountry>
	</xsl:template>
	<xsl:template match="ram:DesignatedProductClassification">
		<cac:CommodityClassification>
			<xsl:if test="ram:ClassCode">
				<cbc:ItemClassificationCode>
					<xsl:if test="ram:ClassCode/@listID">
						<xsl:attribute name="listID" select="ram:ClassCode/@listID"/>
					</xsl:if>
					<xsl:if test="ram:ClassCode/@listVersionID">
						<xsl:attribute name="listVersionID" select="ram:ClassCode/@listVersionID"/>
					</xsl:if>
					<xsl:value-of select="ram:ClassCode"/>
				</cbc:ItemClassificationCode>
			</xsl:if>
		</cac:CommodityClassification>
	</xsl:template>
	<xsl:template match="ram:SpecifiedLineTradeSettlement/ram:ApplicableTradeTax">
		<cac:ClassifiedTaxCategory>
			<cbc:ID>
				<xsl:value-of select="ram:CategoryCode"/>
			</cbc:ID>
			<xsl:if test="ram:RateApplicablePercent">
				<cbc:Percent>
					<xsl:value-of select="ram:RateApplicablePercent"/>
				</cbc:Percent>
			</xsl:if>
			<cac:TaxScheme>
				<cbc:ID>VAT</cbc:ID>
			</cac:TaxScheme>
		</cac:ClassifiedTaxCategory>
	</xsl:template>
	<xsl:template match="ram:SpecifiedTradeProduct/ram:ApplicableProductCharacteristic">
		<cac:AdditionalItemProperty>
			<cbc:Name>
				<xsl:value-of select="ram:Description"/>
			</cbc:Name>
			<cbc:Value>
				<xsl:value-of select="ram:Value"/>
			</cbc:Value>
		</cac:AdditionalItemProperty>
	</xsl:template>
	<!--End Item templates-->
	<!--Price main template start-->
	<xsl:template match="ram:SpecifiedLineTradeAgreement">
		<cac:Price>
			<cbc:PriceAmount currencyID="{$HeaderCurrency}">
				<xsl:value-of select="ram:NetPriceProductTradePrice/ram:ChargeAmount"/>
			</cbc:PriceAmount>
			<xsl:if test="ram:NetPriceProductTradePrice/ram:BasisQuantity">
				<cbc:BaseQuantity>
					<xsl:if test="ram:NetPriceProductTradePrice/ram:BasisQuantity/@unitCode">
						<xsl:attribute name="unitCode" select="ram:NetPriceProductTradePrice/ram:BasisQuantity/@unitCode"/>
					</xsl:if>
					<xsl:value-of select="ram:NetPriceProductTradePrice/ram:BasisQuantity"/>
				</cbc:BaseQuantity>
			</xsl:if>
			<!--Inserting Price Allowance if a discount is found in CII-->
			<xsl:if test="ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ActualAmount">
				<cac:AllowanceCharge>
					<cbc:ChargeIndicator>
						<xsl:value-of select="'false'"/>
					</cbc:ChargeIndicator>
					<cbc:Amount currencyID="{$HeaderCurrency}">
						<xsl:value-of select="ram:GrossPriceProductTradePrice/ram:AppliedTradeAllowanceCharge/ram:ActualAmount"/>
					</cbc:Amount>
				</cac:AllowanceCharge>
			</xsl:if>
		</cac:Price>
	</xsl:template>
	<!--End Price Template-->
	<!--End of Invoice-->
	<!-- ............................................................ -->
	<!--            CreditNote start					              -->
	<!-- ............................................................ -->
	<xsl:template name="CreditNoteMapping">
		<!--Variables for CreditNote header-->
		<xsl:variable name="CustomizationID" select="'urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0'"/>
		<xsl:variable name="ProfileID" select="'urn:fdc:peppol.eu:2017:poacc:billing:01:1.0'"/>
		<xsl:variable name="InvoiceNumber" select="rsm:ExchangedDocument/ram:ID"/>
		<xsl:variable name="IssueDate">
			<xsl:value-of select="substring(rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(rsm:ExchangedDocument/ram:IssueDateTime/udt:DateTimeString, 7,2)"/>
		</xsl:variable>
		<xsl:variable name="CreditNoteTypeCode">
			<xsl:choose>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='261'">381</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='262'">381</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='295'">381</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='308'">381</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='420'">381</xsl:when>
				<xsl:when test="rsm:ExchangedDocument/ram:TypeCode='458'">381</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="rsm:ExchangedDocument/ram:TypeCode"/>
				</xsl:otherwise> 
			</xsl:choose>
		</xsl:variable> 
		<xsl:variable name="HeaderNote" select="rsm:ExchangedDocument/ram:IncludedNote/ram:Content"/>
		<xsl:variable name="TaxPointDate">
			<xsl:value-of select="substring(rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TaxPointDate/udt:DateString, 1,4)"/>-<xsl:value-of select="substring(rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TaxPointDate/udt:DateString, 5,2)"/>-<xsl:value-of select="substring(rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax/ram:TaxPointDate/udt:DateString, 7,2)"/>
		</xsl:variable>
		<xsl:variable name="CurrencyCode" select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceCurrencyCode"/>
		<xsl:variable name="TaxCurrencyCode" select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:TaxCurrencyCode"/>
		<xsl:variable name="AccountingCost" select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID"/>
		<xsl:variable name="BuyerReference" select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerReference"/>
		<!-- Start of BIS CreditNote -->
		<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2">
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
			<cbc:CreditNoteTypeCode>
				<xsl:value-of select="$CreditNoteTypeCode"/>
			</cbc:CreditNoteTypeCode>
			<xsl:apply-templates select="rsm:ExchangedDocument/ram:IncludedNote"/>
			<xsl:if test="string(cbc:TaxPointDate)">
				<cbc:TaxPointDate>
					<xsl:value-of select="cbc:TaxPointDate"/>
				</cbc:TaxPointDate>
			</xsl:if>
			<cbc:DocumentCurrencyCode>
				<xsl:value-of select="$CurrencyCode"/>
			</cbc:DocumentCurrencyCode>
			<xsl:if test="$TaxCurrencyCode">
				<cbc:TaxCurrencyCode>
					<xsl:value-of select="$TaxCurrencyCode"/>
				</cbc:TaxCurrencyCode>
			</xsl:if>
			<xsl:if test="string($AccountingCost)">
				<cbc:AccountingCost>
					<xsl:value-of select="$AccountingCost"/>
				</cbc:AccountingCost>
			</xsl:if>
			<xsl:if test="string($BuyerReference)">
				<cbc:BuyerReference>
					<xsl:value-of select="$BuyerReference"/>
				</cbc:BuyerReference>
			</xsl:if>
			<!--Inserterting InvoicePeriod if present-->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:BillingSpecifiedPeriod"/>
			<!-- OrderReference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerOrderReferencedDocument"/>
			<!-- Billing Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:InvoiceReferencedDocument"/>
			<!-- Despatch document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:DespatchAdviceReferencedDocument"/>
			<!-- Receipt document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ReceivingAdviceReferencedDocument"/>
			<!-- Contract document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:ContractReferencedDocument"/>
			<!-- Additional document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:AdditionalReferencedDocument[ram:TypeCode != '50']"/>
			<!-- Originator document Reference -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement[ram:AdditionalReferencedDocument/ram:TypeCode = '50']"/>
			<!-- AccountingSupplierParty -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- AccountingCustomerParty -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:BuyerTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- PayeeParty -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:PayeeTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- TaxRepresentative Party -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeAgreement/ram:SellerTaxRepresentativeTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- Delivery Header Party -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeDelivery/ram:ShipToTradeParty">
				<xsl:with-param name="GlobalID" select="ram:GlobalID/@schemeID"/>
			</xsl:apply-templates>
			<!-- Payment Means Party -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementPaymentMeans">
		</xsl:apply-templates>
			<!-- PaymentTerms -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradePaymentTerms">
		</xsl:apply-templates>
			<!-- AllowanceCharge -->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeAllowanceCharge">
		</xsl:apply-templates>
			<!-- TaxTotal and total tax amount -->
			<!--Is only created if there is at least one ram:ApplicableTradeTax in CII source and total tax amount is created if present in the CII source-->
			<!--		<xsl:if test="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax">
			<cac:Taxtotal>
				<xsl:if test="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount">
					<cbc:TaxAmount currencyID="{$TotalTaxAmountCurrency}"><xsl:value-of select="$TotalTaxAmount"/></cbc:TaxAmount>
				</xsl:if>
				<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax"/>
			</cac:Taxtotal>
		</xsl:if>
-->
			<xsl:if test="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax">
				<cac:TaxTotal>
					<xsl:if test="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount">
						<cbc:TaxAmount>
							<xsl:choose>
								<xsl:when test="string($TotalTaxAmountCurrency)">
									<xsl:attribute name="currencyID" select="$TotalTaxAmountCurrency"/>
								</xsl:when>
								<xsl:otherwise>
									<xsl:attribute name="currencyID" select="$HeaderCurrency"/>
								</xsl:otherwise>
							</xsl:choose>
							<xsl:value-of select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount[@currencyID=../../../ram:ApplicableHeaderTradeSettlement/ram:InvoiceCurrencyCode]"/>
						</cbc:TaxAmount>
					</xsl:if>
					<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:ApplicableTradeTax"/>
				</cac:TaxTotal>
			</xsl:if>
			<!--If there is a TaxCurrency and  a Invoice total VAT amount in accounting currency a taxtotal with this amount is created-->
			<xsl:if test="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount[@currencyID=../../../ram:ApplicableHeaderTradeSettlement/ram:TaxCurrencyCode]">
				<cac:TaxTotal>
					<cbc:TaxAmount currencyID="{$TaxCurrencyCode}">
						<xsl:value-of select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation/ram:TaxTotalAmount[@currencyID=../../../ram:ApplicableHeaderTradeSettlement/ram:TaxCurrencyCode]"/>
					</cbc:TaxAmount>
				</cac:TaxTotal>
			</xsl:if>
			<!--Totals-->
			<xsl:apply-templates select="rsm:SupplyChainTradeTransaction/ram:ApplicableHeaderTradeSettlement/ram:SpecifiedTradeSettlementHeaderMonetarySummation"/>
			<!--CreditNoteLine Template-->
			<xsl:call-template name="CreditNote"/>
		</CreditNote>
	</xsl:template>
	<!--Template for CreditNote line level-->
	<!--CreditNoteLines-->
	<xsl:template name="CreditNote">
		<xsl:for-each select="rsm:SupplyChainTradeTransaction/ram:IncludedSupplyChainTradeLineItem">
			<!--Variables for line level info-->
			<xsl:variable name="UnitCodeScheme" select="ram:SpecifiedLineTradeDelivery/ram:BilledQuantity/@unitCode"/>
			<cac:CreditNoteLine>
				<!--Mandatory-->
				<cbc:ID>
					<xsl:value-of select="ram:AssociatedDocumentLineDocument/ram:LineID"/>
				</cbc:ID>
				<xsl:if test="ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:Content">
					<cbc:Note>
						<xsl:value-of select="ram:AssociatedDocumentLineDocument/ram:IncludedNote/ram:Content"/>
					</cbc:Note>
				</xsl:if>
				<!--Mandatory-->
				<cbc:CreditedQuantity unitCode="{$UnitCodeScheme}">
					<xsl:value-of select="ram:SpecifiedLineTradeDelivery/ram:BilledQuantity"/>
				</cbc:CreditedQuantity>
				<!--Mandatory-->
				<cbc:LineExtensionAmount currencyID="{$HeaderCurrency}">
					<xsl:value-of select="format-number(ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeSettlementLineMonetarySummation/ram:LineTotalAmount, '0.00')"/>
				</cbc:LineExtensionAmount>
				<xsl:if test="ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID">
					<cbc:AccountingCost>
						<xsl:value-of select="ram:SpecifiedLineTradeSettlement/ram:ReceivableSpecifiedTradeAccountingAccount/ram:ID"/>
					</cbc:AccountingCost>
				</xsl:if>
				<!--Inserting Invoice period if present-->
				<xsl:if test="ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod">
					<cac:InvoicePeriod>
						<cbc:StartDate>
							<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:StartDateTime/udt:DateTimeString, 7,2)"/>
						</cbc:StartDate>
						<cbc:EndDate>
							<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString, 1,4)"/>-<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString, 5,2)"/>-<xsl:value-of select="substring(ram:SpecifiedLineTradeSettlement/ram:BillingSpecifiedPeriod/ram:EndDateTime/udt:DateTimeString, 7,2)"/>
						</cbc:EndDate>
					</cac:InvoicePeriod>
				</xsl:if>
				<!--Inserting OrderReference if present-->
				<xsl:apply-templates select="ram:SpecifiedLineTradeSettlement/ram:BuyerOrderReferencedDocument"/>
				<!--Inserting Addtional document on line level if present-->
				<xsl:apply-templates select="ram:SpecifiedLineTradeSettlement/ram:AdditionalReferencedDocument"/>
				<!--Inserting Line AllowanceCharge if present-->
				<xsl:apply-templates select="ram:SpecifiedLineTradeSettlement/ram:SpecifiedTradeAllowanceCharge"/>
				<!--Inserting Item information on line level-->
				<xsl:apply-templates select="ram:SpecifiedTradeProduct"/>
				<!--Inserting Price information-->
				<xsl:apply-templates select="ram:SpecifiedLineTradeAgreement"/>
			</cac:CreditNoteLine>
		</xsl:for-each>
	</xsl:template>
	<!--Conversion templates for mapping values betweeen CII and BIS in relations to codes and identifiers-->
	<!--Template for handling endpoints and scheme value mappings between CII and PEPPOL BIS 20171214: The endpoints will not be mapped from CII -->
	<xsl:template name="EndpointIDSchemeIDmapping">
		<xsl:param name="SchemeIDUURID"/>
		<xsl:param name="SchemeIDUURIDValue"/>
		<xsl:variable name="SchemeIDMapping">
			<xsl:choose>
				<xsl:when test="$SchemeIDUURID = '0002'">FR:SIRENE</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0007'">SE:ORGNR</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0009'">FR:SIRET</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0037'">FI:OVT</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0060'">DUNS</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0088'">GLN</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0096'">DK:P</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0097'">IT:FTI</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0106'">NL:KVK</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0135'">IT:SIA</xsl:when>
				<xsl:when test="$SchemeIDUURID = '0142'">IT:SECETI</xsl:when>
			</xsl:choose>
		</xsl:variable>
		<!--Build the Endpoint element-->
		<cbc:EndpointID schemeID="{$SchemeIDUURID}">
			<xsl:value-of select="$SchemeIDUURIDValue"/>
		</cbc:EndpointID>
	</xsl:template>
	<!--Template for handling delivery endpoints and scheme value mappings between CII and PEPPOL BIS -->
	<xsl:template name="DeliverySchemeIDmapping">
		<xsl:param name="GlobalIDScheme"/>
		<xsl:param name="GlobalIDSchemeValue"/>
		<xsl:variable name="SchemeIDMapping">
			<xsl:choose>
				<xsl:when test="$GlobalIDScheme = '0002'">FR:SIRENE</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0007'">SE:ORGNR</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0009'">FR:SIRET</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0037'">FI:OVT</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0060'">DUNS</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0088'">GLN</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0096'">DK:P</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0097'">IT:FTI</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0106'">NL:KVK</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0135'">IT:SIA</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0142'">IT:SECETI</xsl:when>
			</xsl:choose>
		</xsl:variable>
		<!--Build the DeliveryID element-->
		<cbc:ID schemeID="{$GlobalIDScheme}">
			<xsl:value-of select="$GlobalIDSchemeValue"/>
		</cbc:ID>
	</xsl:template>
	<!--Template for handling PartyIdentifications and scheme value mappings between CII and PEPPOL BIS -->
	<xsl:template name="PartyIDSchemeIDmapping">
		<xsl:param name="GlobalIDScheme"/>
		<xsl:param name="GlobalIDSchemeValue"/>
		<xsl:variable name="SchemeIDMapping">
			<xsl:choose>
				<xsl:when test="$GlobalIDScheme = '0002'">FR:SIRENE</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0007'">SE:ORGNR</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0009'">FR:SIRET</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0037'">FI:OVT</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0060'">DUNS</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0088'">GLN</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0096'">DK:P</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0097'">IT:FTI</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0106'">NL:KVK</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0135'">IT:SIA</xsl:when>
				<xsl:when test="$GlobalIDScheme = '0142'">IT:SECETI</xsl:when>
			</xsl:choose>
		</xsl:variable>
		<!--Build the PartyIdentification element-->
		<cac:PartyIdentification>
			<cbc:ID schemeID="{$GlobalIDScheme}">
				<xsl:value-of select="$GlobalIDSchemeValue"/>
			</cbc:ID>
		</cac:PartyIdentification>
	</xsl:template>
</xsl:stylesheet>
