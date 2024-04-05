<?php
namespace Fakturaservice\Edelivery\OIOUBL;

/**
 * Electronic Address Scheme code list
 */
abstract class EAS
{
    /*              Danish EAS codes    Meaning         BIS            */
    const P     = "0096";//             P-nummer        0096/DK:P
    const CVR   = "0184";//             CVR-nummer      9902/DK:CVR
    const SE    = "0198";//             SE-nummer       9904/DK:SE
    const CPR   = "9901";//             CPR-nummer      9901/DK:CPR
    const GLN   = "0088";//             GLN/EAN-nummer  0088/GLN

    static function getId($schemeID): string
    {
        switch ($schemeID)
        {
            case EndpointID::DK_P:      return self::P;
            case EndpointID::DK_CVR:    return self::CVR;
            case EndpointID::DK_SE:     return self::SE;
            case EndpointID::DK_CPR:    return self::CPR;
            case EndpointID::GLN:       return self::GLN;
        }
        return ICD::getId($schemeID);
    }
}