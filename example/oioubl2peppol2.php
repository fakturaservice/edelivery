<?php
namespace example;

require_once __DIR__ . '/../src/util/autoload_helper.php';
require_once findComposerAutoload();

use Exception;
use Fakturaservice\Edelivery\Converter2;
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

    $converter      = new Converter2(__DIR__ . "/CII_2_BIS-Billing.xslt");

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
        $peppolFilepath = readline("* Filepath to output PEPPOL document('q' to quit, [ENTER] default to '$defaultPeppolFilepath'): ");
        preg_match('/^.+\.xml$/i', $peppolFilepath, $isOutputFilePathValid);
    }while(
        !isset($isOutputFilePathValid[0]) &&
        (trim(strtolower($peppolFilepath)) !== "q") &&
        (trim(strtolower($peppolFilepath)) !== "")
    );
    if(trim(strtolower($peppolFilepath)) == "q") {
        exit("$printSeparator\nGoodbye!\n");
    }
    elseif(trim(strtolower($peppolFilepath)) == "") {
        $peppolFilepath = $defaultPeppolFilepath;
        preg_match('/^.+\.xml$/i', $peppolFilepath, $isOutputFilePathValid);
    }

    echo "$printSeparator\n";

//    $xmlInputFile   = file_get_contents($oioublFilepath);
    $xmlOutputFile  = $converter->convert($oioublFilepath);

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