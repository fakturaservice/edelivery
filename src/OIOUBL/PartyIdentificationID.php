<?php

namespace Fakturaservice\Edelivery\OIOUBL;

/**
 * Class PartyIdentificationID
 * @package OIOUBL
 */
abstract class PartyIdentificationID
{
    const DUNS = "DUNS";// 		Duns &amp; Bradstreet identifikationsnummer
    const GLN = "GLN";// 	 		GLN-nummer
    const IBAN = "IBAN";// 	 	Internationalt bank-kontonummer
    const ISO_6523 = "ISO 6523";// 	ISO 6523 (OVT-nummer)
    const ZZZ = "ZZZ";// 	 		Bilateralt aftalt kode
    const DK_CPR = "DK:CPR";// 	 	Dansk CPR-nummer
    const DK_CVR = "DK:CVR";// 	 	Dansk CVR-nummer
    const DK_P = "DK:P";// 	 	Dansk P-nummer
    const DK_SE = "DK:SE";// 	 	Dansk SE-nummer
    const DK_TELEFON = "DK:TELEFON";// 	Dansk telefon-nummer
    const FI_ORGNR = "FI:ORGNR";// 	Finsk ORG-nummer
    const IS_KT = "IS:KT";// 	 	Islandsk KT-nummer
    const IS_VSKNR = "IS:VSKNR";// 	Islandsk VSK-nummer
    const NO_EFO = "NO:EFO";// 	 	Norsk EFO-nummer
    const NO_NOBB = "NO:NOBB";// 	 	Norsk NOBB-nummer
    const NO_NODI = "NO:NODI";// 	 	Norsk NODI-nummer
    const NO_ORGNR = "NO:ORGNR";// 	Norsk ORG-nummer
    const NO_VAT = "NO:VAT";// 	 	Norsk VAT-nummer
    const SE_ORGNR = "SE:ORGNR";// 	Svensk ORG-nummer
    const SE_VAT = "SE:VAT";// 	 	Svensk VAT-nummer
}