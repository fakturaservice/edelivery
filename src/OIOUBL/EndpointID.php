<?php

namespace Fakturaservice\Edelivery\OIOUBL;

abstract class EndpointID
{

    //Type/SchemeID                 Scope           ICD     Provider
    //----------------------------------------------------------
    const FR_SIRENE = "FR:SIRENE";//FR              0002    System Information et Repertoire des Entreprise et des Etablissements: SIRENE
    const SE_ORGNR  = "SE:ORGNR";// SE              0007    Organisationsnummer
    const FR_SIRET  = "FR:SIRET";// FR              0009    SIRET-CODE  DU PONT DE NEMOURS
    const FI_OVT    = "FI:OVT";//   FI              0037    LY-tunnus   National Board of Taxes, (Verohallitus)
    const DUNS      = "DUNS";//     international   0060    Data Universal Numbering System (D-U-N-S Number)    Dun & Bradstreet
    const GLN       = "GLN";//      international   0088    Global Location Number  GS1
    const DK_P      = "DK:P";//     DK              0096    SKAT
    const IT_FTI    = "IT:FTI";//   IT              0097    FTI - Ediforum Italia
    const NL_KVK    = "NL:KVK";//   NL              0106    Vereniging van Kamers van Koophandel en Fabrieken in Nederland
    const EU_NAL    = "EU:NAL";//   international   0130    Directorates of the European Commission
    const IT_SIA    = "IT:SIA";//   IT              0135    SIA Object Identifiers
    const IT_SECETI = "IT:SECETI";//IT              0142    SECETI Object Identifiers
    const AU_ABN    = "AU:ABN";//   AU              0151    Australian Business Number (ABN) Scheme
    const CH_UIDB   = "CH:UIDB";//  CH              0183    Swiss Unique Business Identification Number (UIDB)
    const DK_DIGST  = "DK:DIGST";// DK              0184    DIGSTORG
    const JP_SST    = "JP:SST";//   JP              0188    National Tax Agency Japan
    const NL_OINO   = "NL:OINO";//  NL              0190    Organisatie-identificatienummer (OIN)
    const EE_CC     = "EE:CC";//    EE              0191    Company code
    const NO_ORG    = "NO:ORG";//   NO              0192    Organisasjonsnummer
    const UBLBE     = "UBLBE";//    BE              0193    UBL.BE Party Identifier
    const SG_UEN    = "SG:UEN";//   SG              0195    Singapore Nationwide E-Invoice Framework
    const IS_KTNR   = "IS:KTNR";//  IS              0196    Icelandic identifier
    const DK_ERST   = "DK:ERST";//  DK              0198    ERSTORG The Danish Business Authority
    const LEI       = "LEI";//      international   0199    Legal Entity Identifier (LEI)
    const LT_LEC    = "LT:LEC";//   LT              0200    Legal entity code
    const IT_CUUO   = "IT:CUUO";//  IT              0201    Codice Univoco Unità Organizzativa iPA
    const DE_LWID   = "DE:LWID";//  DE              0204    Leitweg-ID
    const BE_EN     = "BE:EN";//    BE              0208
    const GS1		= "GS1";//		international	0209	GS1 identification keys	GS1
    const IT_CFI	= "IT:CFI";//	IT				0210	CODICE FISCALE	Agenzia delle Entrate
    const IT_IVA	= "IT:IVA";//	IT				0211	PARTITA IVA	Agenzia delle Entrate
    const FI_ORG	= "FI:ORG";//	FI				0212	Finnish Organization Identifier	State Treasury of Finland
    const FI_VAT	= "FI:VAT";//	FI				0213	Finnish Organization Value Add Tax Identifier
    const FI_NSI	= "FI:NSI";//	FI				0215	Net service ID	Tieto Finland Oy
    const FI_OVT2	= "FI:OVT2";//	FI				0216	OVTcode	TIEKE- Tietoyhteiskunnan kehittamiskeskus ry
    const DK_CPR	= "DK:CPR";//	DK				9901	Danish Ministry of the Interior and Health
    const DK_CVR	= "DK:CVR";//	DK				9902	The Danish Commerce and Companies Agency
    const DK_SE		= "DK:SE";//	DK				9904	Danish Ministry of Taxation
    const DK_VANS	= "DK:VANS";//	DK				9905	Danish VANS providers	Danish VANS providers
    const IT_VAT	= "IT:VAT";//	IT				9906	Ufficio responsabile gestione partite IVA                   Deprecated
    const IT_CF	    = "IT:CF";//	IT				9907	TAX Authority	                                            Deprecated
    const NO_ORGNR	= "NO:ORGNR";//	NO				9908	Enhetsregisteret ved Bronnoysundregisterne
    const NO_VAT	= "NO:VAT";//	NO				9909	Norwegian VAT number                                        Deprecated
    const HU_VAT	= "HU:VAT";//	HU				9910	Hungary VAT number
    const EU_VAT	= "EU:VAT";//	international	9912	National ministries of Economy		                        Deprecated
    const EU_REID	= "EU:REID";//	international	9913	Business Registers Network	Business Registers Network      Proposed to deprecate
    const AT_VAT	= "AT:VAT";//	AT				9914	Österreichische Umsatzsteuer-Identifikationsnummer
    const AT_GOV	= "AT:GOV";//	AT				9915	Österreichisches Verwaltungs bzw. Organisationskennzeichen
    const AT_CID	= "AT:CID";//	AT				9916	Firmenidentifikationsnummer der Statistik Austria		    Deprecated
    const IS_KT		= "IS:KT";//	IS				9917	Icelandic National Registry		                            Deprecated
    const IBAN		= "IBAN";//		international	9918	SOCIETY FOR WORLDWIDE INTERBANK FINANCIAL, TELECOMMUNICATION
    const AT_KUR	= "AT:KUR";//	AT				9919	Kennziffer des Unternehmensregisters
    const ES_VAT	= "ES:VAT";//	ES				9920	Agencia Española de Administración Tributaria
    const IT_IPA	= "IT:IPA";//	IT				9921	Indice delle Pubbliche Amministrazioni	Indice delle        Deprecated
    const AD_VAT	= "AD:VAT";//	AD				9922	Andorra VAT number
    const AL_VAT	= "AL:VAT";//	AL				9923	Albania VAT number
    const BA_VAT	= "BA:VAT";//	BA				9924	Bosnia and Herzegovina VAT number
    const BE_VAT	= "BE:VAT";//	BE				9925	Belgium VAT number
    const BG_VAT	= "BG:VAT";//	BG				9926	Bulgaria VAT number
    const CH_VAT	= "CH:VAT";//	CH				9927	Switzerland VAT number
    const CY_VAT	= "CY:VAT";//	CY				9928	Cyprus VAT number
    const CZ_VAT	= "CZ:VAT";//	CZ				9929	Czech Republic VAT number
    const DE_VAT	= "DE:VAT";//	DE				9930	Germany VAT number
    const EE_VAT	= "EE:VAT";//	EE				9931	Estonia VAT number
    const GB_VAT	= "GB:VAT";//	GB				9932	United Kingdom VAT number
    const GR_VAT	= "GR:VAT";//	GR				9933	Greece VAT number
    const HR_VAT	= "HR:VAT";//	HR				9934	Croatia VAT number
    const IE_VAT	= "IE:VAT";//	IE				9935	Ireland VAT number
    const LI_VAT	= "LI:VAT";//	LI				9936	Liechtenstein VAT number
    const LT_VAT	= "LT:VAT";//	LT				9937	Lithuania VAT number
    const LU_VAT	= "LU:VAT";//	LU				9938	Luxemburg VAT number
    const LV_VAT	= "LV:VAT";//	LV				9939	Latvia VAT number
    const MC_VAT	= "MC:VAT";//	MC				9940	Monaco VAT number
    const ME_VAT	= "ME:VAT";//	ME				9941	Montenegro VAT number
    const MK_VAT	= "MK:VAT";//	MK				9942	Macedonia, the former Yugoslav Republic of VAT number
    const MT_VAT	= "MT:VAT";//	MT				9943	Malta VAT number
    const NL_VAT	= "NL:VAT";//	NL				9944	Netherlands VAT number
    const PL_VAT	= "PL:VAT";//	PL				9945	Poland VAT number
    const PT_VAT	= "PT:VAT";//	PT				9946	Portugal VAT number
    const RO_VAT	= "RO:VAT";//	RO				9947	Romania VAT number
    const RS_VAT	= "RS:VAT";//	RS				9948	Serbia VAT number
    const SI_VAT	= "SI:VAT";//	SI				9949	Slovenia VAT number
    const SK_VAT	= "SK:VAT";//	SK				9950	Slovakia VAT number
    const SM_VAT	= "SM:VAT";//	SM				9951	San Marino VAT number
    const TR_VAT	= "TR:VAT";//	TR				9952	Turkey VAT number
    const VA_VAT	= "VA:VAT";//	VA				9953	Holy See (Vatican City State) VAT number
    const NL_OIN	= "NL:OIN";//	NL				9954	Dutch Originator's Identification Number                    Deprecated
    const SE_VAT	= "SE:VAT";//	SE				9955	Swedish VAT number
    const BE_CBE	= "BE:CBE";//	BE				9956	Belgian Crossroad Bank of Enterprise                        Deprecated
    const FR_VAT	= "FR:VAT";//	FR				9957	French VAT number
    const DE_LID	= "DE:LID";//	DE				9958	German Leitweg ID                                           Deprecated

    static function getId($icd): string
    {
        switch ($icd) {
            case ICD::FR_SIRENE:	return self::FR_SIRENE;
            case ICD::SE_ORGNR:		return self::SE_ORGNR;
            case ICD::FR_SIRET:		return self::FR_SIRET;
            case ICD::FI_OVT:		return self::FI_OVT;
            case ICD::DUNS:			return self::DUNS;
            case ICD::GLN:			return self::GLN;
            case ICD::DK_P:			return self::DK_P;
            case ICD::IT_FTI:		return self::IT_FTI;
            case ICD::NL_KVK:		return self::NL_KVK;
            case ICD::EU_NAL:		return self::EU_NAL;
            case ICD::IT_SIA:		return self::IT_SIA;
            case ICD::IT_SECETI:	return self::IT_SECETI;
            case ICD::AU_ABN:		return self::AU_ABN;
            case ICD::CH_UIDB:		return self::CH_UIDB;
            case ICD::DK_DIGST:		return self::DK_CVR;//return self::DK_DIGST;
            case ICD::JP_SST:		return self::JP_SST;
            case ICD::NL_OINO:		return self::NL_OINO;
            case ICD::EE_CC:		return self::EE_CC;
            case ICD::NO_ORG:		return self::NO_ORG;
            case ICD::UBLBE:		return self::UBLBE;
            case ICD::SG_UEN:		return self::SG_UEN;
            case ICD::IS_KTNR:		return self::IS_KTNR;
            case ICD::DK_ERST:		return self::DK_SE;//return self::DK_ERST;
            case ICD::LEI:			return self::LEI;
            case ICD::LT_LEC:		return self::LT_LEC;
            case ICD::IT_CUUO:		return self::IT_CUUO;
            case ICD::DE_LWID:		return self::DE_LWID;
            case ICD::BE_EN:		return self::BE_EN;
            case ICD::GS1:			return self::GS1;
            case ICD::IT_CFI:		return self::IT_CFI;
            case ICD::IT_IVA:		return self::IT_IVA;
            case ICD::FI_ORG:		return self::FI_ORG;
            case ICD::FI_VAT:		return self::FI_VAT;
            case ICD::FI_NSI:		return self::FI_NSI;
            case ICD::FI_OVT2:		return self::FI_OVT2;
            case ICD::DK_CPR:		return self::DK_CPR;
            case ICD::DK_CVR:		return self::DK_CVR;
            case ICD::DK_SE:		return self::DK_SE;
            case ICD::DK_VANS:		return self::DK_VANS;
            case ICD::IT_VAT:		return self::IT_VAT;
            case ICD::IT_CF:		return self::IT_CF;
            case ICD::NO_ORGNR:		return self::NO_ORGNR;
            case ICD::NO_VAT:		return self::NO_VAT;
            case ICD::HU_VAT:		return self::HU_VAT;
            case ICD::EU_VAT:		return self::EU_VAT;
            case ICD::EU_REID:		return self::EU_REID;
            case ICD::AT_VAT:		return self::AT_VAT;
            case ICD::AT_GOV:		return self::AT_GOV;
            case ICD::AT_CID:		return self::AT_CID;
            case ICD::IS_KT:		return self::IS_KT;
            case ICD::IBAN:			return self::IBAN;
            case ICD::AT_KUR:		return self::AT_KUR;
            case ICD::ES_VAT:		return self::ES_VAT;
            case ICD::IT_IPA:		return self::IT_IPA;
            case ICD::AD_VAT:		return self::AD_VAT;
            case ICD::AL_VAT:		return self::AL_VAT;
            case ICD::BA_VAT:		return self::BA_VAT;
            case ICD::BE_VAT:		return self::BE_VAT;
            case ICD::BG_VAT:		return self::BG_VAT;
            case ICD::CH_VAT:		return self::CH_VAT;
            case ICD::CY_VAT:		return self::CY_VAT;
            case ICD::CZ_VAT:		return self::CZ_VAT;
            case ICD::DE_VAT:		return self::DE_VAT;
            case ICD::EE_VAT:		return self::EE_VAT;
            case ICD::GB_VAT:		return self::GB_VAT;
            case ICD::GR_VAT:		return self::GR_VAT;
            case ICD::HR_VAT:		return self::HR_VAT;
            case ICD::IE_VAT:		return self::IE_VAT;
            case ICD::LI_VAT:		return self::LI_VAT;
            case ICD::LT_VAT:		return self::LT_VAT;
            case ICD::LU_VAT:		return self::LU_VAT;
            case ICD::LV_VAT:		return self::LV_VAT;
            case ICD::MC_VAT:		return self::MC_VAT;
            case ICD::ME_VAT:		return self::ME_VAT;
            case ICD::MK_VAT:		return self::MK_VAT;
            case ICD::MT_VAT:		return self::MT_VAT;
            case ICD::NL_VAT:		return self::NL_VAT;
            case ICD::PL_VAT:		return self::PL_VAT;
            case ICD::PT_VAT:		return self::PT_VAT;
            case ICD::RO_VAT:		return self::RO_VAT;
            case ICD::RS_VAT:		return self::RS_VAT;
            case ICD::SI_VAT:		return self::SI_VAT;
            case ICD::SK_VAT:		return self::SK_VAT;
            case ICD::SM_VAT:		return self::SM_VAT;
            case ICD::TR_VAT:		return self::TR_VAT;
            case ICD::VA_VAT:		return self::VA_VAT;
            case ICD::NL_OIN:		return self::NL_OIN;
            case ICD::SE_VAT:		return self::SE_VAT;
            case ICD::BE_CBE:		return self::BE_CBE;
            case ICD::FR_VAT:		return self::FR_VAT;
            case ICD::DE_LID:		return self::DE_LID;
        }
        return $icd;
    }
}