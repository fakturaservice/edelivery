<?php
namespace example;

require_once __DIR__ . '/../vendor/autoload.php';

use Exception;
use Fakturaservice\Edelivery\Converter;
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

    $converter      = new Converter(new Logger($debugLevel));

    echo "\n$printSeparator";
    do{
        $oioublFilepath = readline("* Filepath to input OIOUBL document('q' to quit): ");
        preg_match('/^.+\.xml$/i', $oioublFilepath, $isInputFilePathValid);
    }while(!isset($isInputFilePathValid[0]) && (trim(strtolower($oioublFilepath)) !== "q"));
    if(trim(strtolower($oioublFilepath)) == "q") {
        exit("$printSeparator\nGoodbye!\n");
    }
    $defaultPeppolFilepath = preg_replace('/(\.xml$)/', '-to-Peppol$1', $oioublFilepath);

    do{
        $peppolFilepath = readline("* Filepath to output PEPPOL document('q' to quit, 'd' default to '$defaultPeppolFilepath'): ");
        preg_match('/^.+\.xml$/i', $peppolFilepath, $isOutputFilePathValid);
    }while(
        !isset($isOutputFilePathValid[0]) &&
        (trim(strtolower($peppolFilepath)) !== "q") &&
        (trim(strtolower($peppolFilepath)) !== "d")
    );
    if(trim(strtolower($peppolFilepath)) == "q") {
        exit("$printSeparator\nGoodbye!\n");
    }
    elseif(trim(strtolower($peppolFilepath)) == "d") {
        $peppolFilepath = $defaultPeppolFilepath;
        preg_match('/^.+\.xml$/i', $peppolFilepath, $isOutputFilePathValid);
    }

    echo "$printSeparator\n";

    $xmlInputFile   = file_get_contents($oioublFilepath);
    $xmlOutputFile  = $converter->OIOUBLtoBIS3($xmlInputFile);

    if(isset($isOutputFilePathValid[0]))
    {
        $log->log("Writing output file to: '$peppolFilepath'");
        file_put_contents($peppolFilepath, $xmlOutputFile);
    }
    else
    {
        $log->log("'$peppolFilepath' is not a valid filepath. Writing file to stdout:\n$xmlOutputFile\n");
    }

} catch (Exception $e)
{
}