<?php
namespace example;

require_once __DIR__ . '/../src/util/autoload_helper.php';
require_once findComposerAutoload();

use Exception;
use Fakturaservice\Edelivery\OIOUBL\NetworkType;
use Fakturaservice\Edelivery\OxalisWrapper;
use Fakturaservice\Edelivery\util\Logger;

//putenv('APP_ENV=prod');
putenv('APP_ENV=dev');

try
{
    $printSeparator = "*****************************************************************************\n";

    $environment    = getenv('APP_ENV');
    $configuration  = parse_ini_file(__DIR__ . "/config.ini", true);
    $debugLevel     = $configuration[$environment]["debugLevel"];
    $log            = new Logger($debugLevel);
    $log->setChannel(basename(__FILE__));

    echo "\n$printSeparator";
    do{
        $inputUblFilepath = readline("* Filepath to input UBL document('q' to quit): ");
        $inputUblFilepath = trim($inputUblFilepath);
        if(strtolower($inputUblFilepath) == "q") {exit("$printSeparator\nGoodbye!\n");}
        preg_match('/^.+\.xml$/i', $inputUblFilepath, $isInputFilePathValid);

    }while(!isset($isInputFilePathValid[0]));
    $defaultWrappedFilepath = preg_replace('/(\.xml$)/', '-wrapped$1', $inputUblFilepath);

    do{
        $wrappedFilepath = readline("* Filepath to output wrapped document('q' to quit, [ENTER] default to '$defaultWrappedFilepath'): ");
        preg_match('/^.+\.xml$/i', $wrappedFilepath, $isOutputFilePathValid);
    }while(
        !isset($isOutputFilePathValid[0]) &&
        (trim(strtolower($wrappedFilepath)) !== "q") &&
        (trim(strtolower($wrappedFilepath)) !== "")
    );
    if(trim(strtolower($wrappedFilepath)) == "q") {
        exit("$printSeparator\nGoodbye!\n");
    }
    elseif(trim(strtolower($wrappedFilepath)) == "") {
        $wrappedFilepath = $defaultWrappedFilepath;
        preg_match('/^.+\.xml$/i', $wrappedFilepath, $isOutputFilePathValid);
    }

    echo "$printSeparator\n";

    $xml            = file_get_contents($inputUblFilepath);

    $oxalisWrapper  = new OxalisWrapper($xml, new Logger($debugLevel));
    $xml            = $oxalisWrapper->wrapSBD(NetworkType::PEPPOL_AS4);

    file_put_contents($wrappedFilepath, $xml);


} catch (Exception $e)
{
}