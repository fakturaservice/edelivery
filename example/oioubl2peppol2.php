<?php
namespace example;

require_once __DIR__ . '/../src/util/autoload_helper.php';
require_once findComposerAutoload();

use DOMDocument;
use Exception;
use Fakturaservice\Edelivery\Converter;
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

    echo "\n$printSeparator";
    do{
        $xsltFilePath   = readline("* Filepath to XSL Stylesheet('q' to quit, [ENTER] to default): ");
        $xsltFilePath   = trim($xsltFilePath);
        if(strtolower($xsltFilePath) == "q") {exit("$printSeparator\nGoodbye!\n");}
        if(strtolower($xsltFilePath) == "") {$xsltFilePath = __DIR__ . "/../src/resources/XSLT/OIOUBL-21_2_PEPPOL-BIS3.xslt";}
        preg_match('/^.+\.xslt$/i', $xsltFilePath, $isXsltFilePathValid);

        $oioublFilepath = readline("* Filepath to input OIOUBL document('q' to quit): ");
        $oioublFilepath = trim($oioublFilepath);
        if(strtolower($oioublFilepath) == "q") {exit("$printSeparator\nGoodbye!\n");}
        preg_match('/^.+\.xml$/i', $oioublFilepath, $isInputFilePathValid);

    }while(!isset($isInputFilePathValid[0]) && !isset($isXsltFilePathValid[0]));
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

    // HACK: Converting EndpointIds
    $dom = new DOMDocument;
    $dom->load($oioublFilepath);
    $converter1     = new Converter(new Logger($debugLevel));
    $converter1->convertAllSchemeIDsAndCleanUpEndpointID($dom);
    $outputXml = $dom->saveXML();
    file_put_contents($oioublFilepath, $outputXml);

    $converter      = new Converter2(new Logger($debugLevel), $xsltFilePath);
    $xmlOutputFile  = $converter->convert($oioublFilepath);

    $xml = simplexml_load_string($xmlOutputFile);

    // Check if the root element is 'Error' and contains 'Errortext'
    if ($xml !== false && $xml->getName() == 'Error' && isset($xml->Errortext))
    {
        $log->log("Error in converting input XML: '$xml->Errortext'", Logger::LV_1, Logger::LOG_ERR);
    }
    else if(isset($isOutputFilePathValid[0]))
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