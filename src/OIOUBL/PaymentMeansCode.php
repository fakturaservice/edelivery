<?php

namespace Fakturaservice\Edelivery\OIOUBL;

abstract class PaymentMeansCode
{
    const Instrument_not_defined = 1;    // Payment means are not provided with the invoice
    const Cash = 10;   // Payments in cash
    const Cheque = 20;   // Payment by cheque
    const Debit_transfer = 31;   // Bank deposits into the stated account, manual or electronic transfers
    const Payment_to_bank_account = 42;   // Payment to bank account
    const Bank_card = 48;   // Credit card, purchasing card and other credit methods
    const Direct_debit = 49;   // The amount is to be, or has been, directly debited to a bank account
    const Payment_by_postgiro = 50;   // Payment via a giro system
    const Reference_giro = 93;   // Ordering customer tells the bank to use the payment system 'Reference Giro'
    const Clearing_between_partners = 97;   // Settlement is made by separate arrangements between partners
}