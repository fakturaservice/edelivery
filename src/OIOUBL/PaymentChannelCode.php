<?php

namespace Fakturaservice\Edelivery\OIOUBL;

abstract class PaymentChannelCode
{
    const BBAN = "BBAN";           // Bank account identified by domestic means
    const DK_BANK = "DK:BANK";        // Danish Bank Account identified by domestic means
    const DK_FIK = "DK:FIK";         // Danish FIK Transfer
    const DK_GIRO = "DK:GIRO";        // Danish Giro Account
    const DK_NEMKONTO = "DK:NEMKONTO";    // Direct transfer to a NemKonto registered bank account (Denmark)
    const FI_BANK = "FI:BANK";        // Finnish Bank Account identified by domestic means
    const FI_GIRO = "FI:GIRO";        // Finnish Giro Account
    const GB_BACS = "GB:BACS";        // British Bankers Automated Clearing System
    const GB_BANK = "GB:BANK";        // British Bank Account identified by domestic means
    const GB_GIRO = "GB:GIRO";        // British Giro Account
    const IBAN = "IBAN";           // International Bank Account Number
    const IS_BANK = "IS:BANK";        // Icelandic Bank Account identified by domestic means
    const IS_GIRO = "IS:GIRO";        // Icelandic Giro Account
    const IS_IK66 = "IS:IK66";        // Icelandic bank claiming system
    const IS_RB = "IS:RB";          // Icelandic Bank Account Transfer
    const NO_BANK = "NO:BANK";        // Norwegian Bank Account
    const SE_BANKGIRO = "SE:BANKGIRO";    // Swedish Bankgiro Account
    const SE_PLUSGIRO = "SE:PLUSGIRO";    // Swedish Plusgiro Account
    const SWIFTUS = "SWIFTUS";        // SWIFT payment to the US
    const ZZZ = "ZZZ";            // Mutually defined
}