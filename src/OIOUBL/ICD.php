<?php

namespace Fakturaservice\Edelivery\OIOUBL;

/**
 * International Code Designator
 */
abstract class ICD
{
    const FR_SIRENE = "0002";//		System Information et Repertoire des Entreprise et des Etablissements: SIRENE
    const SE_ORGNR  = "0007";//		Organisationsnummer
    const FR_SIRET  = "0009";//		SIRET-CODE  DU PONT DE NEMOURS
    const FI_OVT    = "0037";//		LY-tunnus   National Board of Taxes, (Verohallitus)
    const DUNS      = "0060";//		Data Universal Numbering System (D-U-N-S Number)    Dun & Bradstreet
    const GLN       = "0088";//		Global Location Number  GS1
    const DK_P      = "0096";//		SKAT
    const IT_FTI    = "0097";//		FTI - Ediforum Italia
    const NL_KVK    = "0106";//		Vereniging van Kamers van Koophandel en Fabrieken in Nederland
    const EU_NAL    = "0130";//		Directorates of the European Commission
    const IT_SIA    = "0135";//		SIA Object Identifiers
    const IT_SECETI = "0142";//		SECETI Object Identifiers
    const AU_ABN    = "0151";//		Australian Business Number (ABN) Scheme
    const CH_UIDB   = "0183";//		Swiss Unique Business Identification Number (UIDB)
    const DK_DIGST  = "0184";//		DIGSTORG
    const JP_SST    = "0188";//		National Tax Agency Japan
    const NL_OINO   = "0190";//		Organisatie-identificatienummer (OIN)
    const EE_CC     = "0191";//		Company code
    const NO_ORG    = "0192";//		Organisasjonsnummer
    const UBLBE     = "0193";//		UBL.BE Party Identifier
    const SG_UEN    = "0195";//		Singapore Nationwide E-Invoice Framework
    const IS_KTNR	= "0196";//		Icelandic identifier
    const DK_ERST	= "0198";//		ERSTORG The Danish Business Authority
    const LEI		= "0199";//		Legal Entity Identifier (LEI)
    const LT_LEC	= "0200";//		Legal entity code
    const IT_CUUO	= "0201";//		Codice Univoco Unità Organizzativa iPA
    const DE_LWID	= "0204";//		Leitweg-ID
    const BE_EN		= "0208";//		Numero d'entreprise / ondernemingsnummer / Unternehmensnummer
    const GS1		= "0209";//		GS1 identification keys	GS1
    const IT_CFI	= "0210";//		CODICE FISCALE	Agenzia delle Entrate
    const IT_IVA	= "0211";//		PARTITA IVA	Agenzia delle Entrate
    const FI_ORG	= "0212";//		Finnish Organization Identifier	State Treasury of Finland
    const FI_VAT	= "0213";//		Finnish Organization Value Add Tax Identifier
    const FI_NSI	= "0215";//		Net service ID	Tieto Finland Oy
    const FI_OVT2	= "0216";//		OVTcode	TIEKE- Tietoyhteiskunnan kehittamiskeskus ry
    const DK_CPR	= "9901";//		Danish Ministry of the Interior and Health
    const DK_CVR	= "9902";//		The Danish Commerce and Companies Agency
    const DK_SE		= "9904";//		Danish Ministry of Taxation
    const DK_VANS	= "9905";//		Danish VANS providers	Danish VANS providers
    const IT_VAT	= "9906";//		Ufficio responsabile gestione partite IVA                   Deprecated
    const IT_CF	    = "9907";//	    TAX Authority	                                            Deprecated
    const NO_ORGNR	= "9908";//		Enhetsregisteret ved Bronnoysundregisterne
    const NO_VAT	= "9909";//		Norwegian VAT number                                        Deprecated
    const HU_VAT	= "9910";//		Hungary VAT number
    const EU_VAT	= "9912";//		National ministries of Economy		                        Deprecated
    const EU_REID	= "9913";//		Business Registers Network	Business Registers Network      Proposed to deprecate
    const AT_VAT	= "9914";//		Österreichische Umsatzsteuer-Identifikationsnummer
    const AT_GOV	= "9915";//		Österreichisches Verwaltungs bzw. Organisationskennzeichen
    const AT_CID	= "9916";//		Firmenidentifikationsnummer der Statistik Austria		    Deprecated
    const IS_KT		= "9917";//		Icelandic National Registry		                            Deprecated
    const IBAN		= "9918";//		SOCIETY FOR WORLDWIDE INTERBANK FINANCIAL, TELECOMMUNICATION
    const AT_KUR	= "9919";//		Kennziffer des Unternehmensregisters
    const ES_VAT	= "9920";//		Agencia Española de Administración Tributaria
    const IT_IPA	= "9921";//		Indice delle Pubbliche Amministrazioni	Indice delle        Deprecated
    const AD_VAT	= "9922";//		Andorra VAT number
    const AL_VAT	= "9923";//		Albania VAT number
    const BA_VAT	= "9924";//		Bosnia and Herzegovina VAT number
    const BE_VAT	= "9925";//		Belgium VAT number
    const BG_VAT	= "9926";//		Bulgaria VAT number
    const CH_VAT	= "9927";//		Switzerland VAT number
    const CY_VAT	= "9928";//		Cyprus VAT number
    const CZ_VAT	= "9929";//		Czech Republic VAT number
    const DE_VAT	= "9930";//		Germany VAT number
    const EE_VAT	= "9931";//		Estonia VAT number
    const GB_VAT	= "9932";//		United Kingdom VAT number
    const GR_VAT	= "9933";//		Greece VAT number
    const HR_VAT	= "9934";//		Croatia VAT number
    const IE_VAT	= "9935";//		Ireland VAT number
    const LI_VAT	= "9936";//		Liechtenstein VAT number
    const LT_VAT	= "9937";//		Lithuania VAT number
    const LU_VAT	= "9938";//		Luxemburg VAT number
    const LV_VAT	= "9939";//		Latvia VAT number
    const MC_VAT	= "9940";//		Monaco VAT number
    const ME_VAT	= "9941";//		Montenegro VAT number
    const MK_VAT	= "9942";//		Macedonia, the former Yugoslav Republic of VAT number
    const MT_VAT	= "9943";//		Malta VAT number
    const NL_VAT	= "9944";//		Netherlands VAT number
    const PL_VAT	= "9945";//		Poland VAT number
    const PT_VAT	= "9946";//		Portugal VAT number
    const RO_VAT	= "9947";//		Romania VAT number
    const RS_VAT	= "9948";//		Serbia VAT number
    const SI_VAT	= "9949";//		Slovenia VAT number
    const SK_VAT	= "9950";//		Slovakia VAT number
    const SM_VAT	= "9951";//		San Marino VAT number
    const TR_VAT	= "9952";//		Turkey VAT number
    const VA_VAT	= "9953";//		Holy See (Vatican City State) VAT number
    const NL_OIN	= "9954";//		Dutch Originator's Identification Number                    Deprecated
    const SE_VAT	= "9955";//		Swedish VAT number
    const BE_CBE	= "9956";//		Belgian Crossroad Bank of Enterprise                        Deprecated
    const FR_VAT	= "9957";//		French VAT number
    const DE_LID	= "9958";//		German Leitweg ID                                           Deprecated

    static function getId($schemeID): string
    {
        switch ($schemeID) {
            case EndpointID::FR_SIRENE:	return self::FR_SIRENE;
            case EndpointID::SE_ORGNR:	return self::SE_ORGNR;
            case EndpointID::FR_SIRET:	return self::FR_SIRET;
            case EndpointID::FI_OVT:	return self::FI_OVT;
            case EndpointID::DUNS:		return self::DUNS;
            case EndpointID::GLN:		return self::GLN;
            case EndpointID::DK_P:		return self::DK_P;
            case EndpointID::IT_FTI:	return self::IT_FTI;
            case EndpointID::NL_KVK:	return self::NL_KVK;
            case EndpointID::EU_NAL:	return self::EU_NAL;
            case EndpointID::IT_SIA:	return self::IT_SIA;
            case EndpointID::IT_SECETI:	return self::IT_SECETI;
            case EndpointID::AU_ABN:	return self::AU_ABN;
            case EndpointID::CH_UIDB:	return self::CH_UIDB;
            case EndpointID::DK_DIGST:	return self::DK_DIGST;
            case EndpointID::JP_SST:	return self::JP_SST;
            case EndpointID::NL_OINO:	return self::NL_OINO;
            case EndpointID::EE_CC:		return self::EE_CC;
            case EndpointID::NO_ORG:	return self::NO_ORG;
            case EndpointID::UBLBE:		return self::UBLBE;
            case EndpointID::SG_UEN:	return self::SG_UEN;
            case EndpointID::IS_KTNR:	return self::IS_KTNR;
            case EndpointID::DK_ERST:	return self::DK_ERST;
            case EndpointID::LEI:		return self::LEI;
            case EndpointID::LT_LEC:	return self::LT_LEC;
            case EndpointID::IT_CUUO:	return self::IT_CUUO;
            case EndpointID::DE_LWID:	return self::DE_LWID;
            case EndpointID::BE_EN:		return self::BE_EN;
            case EndpointID::GS1:		return self::GS1;
            case EndpointID::IT_CFI:	return self::IT_CFI;
            case EndpointID::IT_IVA:	return self::IT_IVA;
            case EndpointID::FI_ORG:	return self::FI_ORG;
            case EndpointID::FI_VAT:	return self::FI_VAT;
            case EndpointID::FI_NSI:	return self::FI_NSI;
            case EndpointID::FI_OVT2:	return self::FI_OVT2;
            case EndpointID::DK_CPR:	return self::DK_CPR;
            case EndpointID::DK_CVR:	return self::DK_CVR;
            case EndpointID::DK_SE:		return self::DK_SE;
            case EndpointID::DK_VANS:	return self::DK_VANS;
            case EndpointID::IT_VAT:	return self::IT_VAT;
            case EndpointID::IT_CF:	    return self::IT_CF;
            case EndpointID::NO_ORGNR:	return self::NO_ORGNR;
            case EndpointID::NO_VAT:	return self::NO_VAT;
            case EndpointID::HU_VAT:	return self::HU_VAT;
            case EndpointID::EU_VAT:	return self::EU_VAT;
            case EndpointID::EU_REID:	return self::EU_REID;
            case EndpointID::AT_VAT:	return self::AT_VAT;
            case EndpointID::AT_GOV:	return self::AT_GOV;
            case EndpointID::AT_CID:	return self::AT_CID;
            case EndpointID::IS_KT:		return self::IS_KT;
            case EndpointID::IBAN:		return self::IBAN;
            case EndpointID::AT_KUR:	return self::AT_KUR;
            case EndpointID::ES_VAT:	return self::ES_VAT;
            case EndpointID::IT_IPA:	return self::IT_IPA;
            case EndpointID::AD_VAT:	return self::AD_VAT;
            case EndpointID::AL_VAT:	return self::AL_VAT;
            case EndpointID::BA_VAT:	return self::BA_VAT;
            case EndpointID::BE_VAT:	return self::BE_VAT;
            case EndpointID::BG_VAT:	return self::BG_VAT;
            case EndpointID::CH_VAT:	return self::CH_VAT;
            case EndpointID::CY_VAT:	return self::CY_VAT;
            case EndpointID::CZ_VAT:	return self::CZ_VAT;
            case EndpointID::DE_VAT:	return self::DE_VAT;
            case EndpointID::EE_VAT:	return self::EE_VAT;
            case EndpointID::GB_VAT:	return self::GB_VAT;
            case EndpointID::GR_VAT:	return self::GR_VAT;
            case EndpointID::HR_VAT:	return self::HR_VAT;
            case EndpointID::IE_VAT:	return self::IE_VAT;
            case EndpointID::LI_VAT:	return self::LI_VAT;
            case EndpointID::LT_VAT:	return self::LT_VAT;
            case EndpointID::LU_VAT:	return self::LU_VAT;
            case EndpointID::LV_VAT:	return self::LV_VAT;
            case EndpointID::MC_VAT:	return self::MC_VAT;
            case EndpointID::ME_VAT:	return self::ME_VAT;
            case EndpointID::MK_VAT:	return self::MK_VAT;
            case EndpointID::MT_VAT:	return self::MT_VAT;
            case EndpointID::NL_VAT:	return self::NL_VAT;
            case EndpointID::PL_VAT:	return self::PL_VAT;
            case EndpointID::PT_VAT:	return self::PT_VAT;
            case EndpointID::RO_VAT:	return self::RO_VAT;
            case EndpointID::RS_VAT:	return self::RS_VAT;
            case EndpointID::SI_VAT:	return self::SI_VAT;
            case EndpointID::SK_VAT:	return self::SK_VAT;
            case EndpointID::SM_VAT:	return self::SM_VAT;
            case EndpointID::TR_VAT:	return self::TR_VAT;
            case EndpointID::VA_VAT:	return self::VA_VAT;
            case EndpointID::NL_OIN:	return self::NL_OIN;
            case EndpointID::SE_VAT:	return self::SE_VAT;
            case EndpointID::BE_CBE:	return self::BE_CBE;
            case EndpointID::FR_VAT:	return self::FR_VAT;
            case EndpointID::DE_LID:	return self::DE_LID;
        }
        return (string)$schemeID;
    }
    static function getIdsByAlpha2Code($alpha2Code): array
    {
        switch ($alpha2Code) {
            case "FR":	return [self::FR_SIRENE, self::FR_SIRET, self::FR_VAT];
            case "SE":	return [self::SE_ORGNR, self::SE_VAT];
            case "FI":	return [self::FI_OVT, self::FI_ORG, self::FI_VAT, self::FI_OVT2, self::FI_NSI];
            case "DK":	return [self::DK_P, self::DK_CVR, self::DK_SE, self::DK_CPR, self::DK_DIGST, self::DK_ERST, self::DK_VANS];
            case "IT":	return [self::IT_FTI, self::IT_CF, self::IT_CFI, self::IT_CUUO, self::IT_IPA, self::IT_IVA, self::IT_SECETI, self::IT_SIA, self::IT_VAT];
            case "NL":	return [self::NL_KVK, self::NL_OIN, self::NL_VAT, self::NL_OINO];
            case "AU":	return [self::AU_ABN];
            case "CH":	return [self::CH_UIDB, self::CH_VAT];
            case "JP":	return [self::JP_SST];
            case "EE":	return [self::EE_CC, self::EE_VAT];
            case "NO":	return [self::NO_ORG, self::NO_VAT, self::NO_ORGNR];
            case "SG":	return [self::SG_UEN];
            case "IS":	return [self::IS_KTNR, self::IS_KT];
            case "LT":	return [self::LT_LEC, self::LT_VAT];
            case "DE":	return [self::DE_LWID, self::DE_LID, self::DE_VAT];
            case "HU":	return [self::HU_VAT];
            case "AT":	return [self::AT_VAT, self::AT_CID, self::AT_GOV, self::AT_KUR];
            case "ES":	return [self::ES_VAT];
            case "AD":	return [self::AD_VAT];
            case "AL":	return [self::AL_VAT];
            case "BA":	return [self::BA_VAT];
            case "BG":	return [self::BG_VAT];
            case "CY":	return [self::CY_VAT];
            case "CZ":	return [self::CZ_VAT];
            case "GB":	return [self::GB_VAT];
            case "GR":	return [self::GR_VAT];
            case "HR":	return [self::HR_VAT];
            case "IE":	return [self::IE_VAT];
            case "LI":	return [self::LI_VAT];
            case "LU":	return [self::LU_VAT];
            case "LV":	return [self::LV_VAT];
            case "MC":	return [self::MC_VAT];
            case "ME":	return [self::ME_VAT];
            case "MK":	return [self::MK_VAT];
            case "MT":	return [self::MT_VAT];
            case "PL":	return [self::PL_VAT];
            case "PT":	return [self::PT_VAT];
            case "RO":	return [self::RO_VAT];
            case "RS":	return [self::RS_VAT];
            case "SI":	return [self::SI_VAT];
            case "SK":	return [self::SK_VAT];
            case "SM":	return [self::SM_VAT];
            case "TR":	return [self::TR_VAT];
            case "VA":	return [self::VA_VAT];
        }
        return [self::GLN, self::DUNS, self::EU_NAL, self::LEI, self::GS1, self::EU_VAT, self::EU_REID, self::IBAN];
    }
}