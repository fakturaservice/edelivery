<?php

namespace Fakturaservice\Edelivery\OIOUBL;

abstract class NetworkType
{
    const NemHandel_RASP = 1;
    const PEPPOL_AS4     = 3;
    const NemHandel_AS4  = 4;

    static public function getName($code): string
    {
        switch($code)
        {
            case self::NemHandel_RASP:  return "NemHandel / RASP";  //1
            case self::PEPPOL_AS4:      return "PEPPOL / AS4";      //3
            case self::NemHandel_AS4:   return "NemHandel / AS4";   //4
            default:                    return "Unknown";
        }
    }
    static public function getIds($name): int
    {
        switch($name)
        {
            case "NemHandel / RASP":    return self::NemHandel_RASP;  //1
            case "PEPPOL / AS4":        return self::PEPPOL_AS4;      //3
            case "NemHandel / AS4":     return self::NemHandel_AS4;   //4
            default:                    return 0;
        }
    }
}