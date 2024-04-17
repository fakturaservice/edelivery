<?php

namespace Fakturaservice\Edelivery\OIOUBL;

abstract class CatalogueType
{

    const ApplicationResponse = "ApplicationResponse";
    const AttachedDocument = "AttachedDocument";
    const BillOfLading = "BillOfLading";
    const Catalogue = "Catalogue";
    const CatalogueDeletion = "CatalogueDeletion";
    const CatalogueItemSpecificationUpdate = "CatalogueItemSpecificationUpdate";
    const CataloguePricingUpdate = "CataloguePricingUpdate";
    const CatalogueRequest = "CatalogueRequest";
    const CertificateOfOrigin = "CertificateOfOrigin";
    const CreditNote = "CreditNote";
    const DebitNote = "DebitNote";
    const DespatchAdvice = "DespatchAdvice";
    const ForwardingInstructions = "ForwardingInstructions";
    const FreightInvoice = "FreightInvoice";
    const Invoice = "Invoice";
    const Order = "Order";
    const OrderCancellation = "OrderCancellation";
    const OrderChange = "OrderChange";
    const OrderResponse = "OrderResponse";
    const OrderResponseSimple = "OrderResponseSimple";
    const PackingList = "PackingList";
    const Quotation = "Quotation";
    const ReceiptAdvice = "ReceiptAdvice";
    const Reminder = "Reminder";
    const RemittanceAdvice = "RemittanceAdvice";
    const RequestForQuotation = "RequestForQuotation";
    const SelfBilledCreditNote = "SelfBilledCreditNote";
    const SelfBilledInvoice = "SelfBilledInvoice";
    const Statement = "Statement";
    const TransportationStatus = "TransportationStatus";
    const Waybill = "Waybill";

    //reporting documents
    const TransactionStatisticsReport   = "TransactionStatisticsReport";
    const EndUserStatisticsReport       = "EndUserStatisticsReport";
}