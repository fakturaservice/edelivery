<?php
namespace example;

require_once __DIR__ . '/../src/util/autoload_helper.php';
require_once findComposerAutoload();

use Exception;
use Fakturaservice\Edelivery\NemLookUpCli;
use Fakturaservice\Edelivery\OIOUBL\NetworkType;
use Fakturaservice\Edelivery\OIOUBL\ProfileID;
use Fakturaservice\Edelivery\OxalisWrapper;
use Fakturaservice\Edelivery\util\Logger;

putenv('APP_ENV=prod');
//putenv('APP_ENV=dev');

try {
    $printSeparator = "*****************************************************************************\n";

    $environment = getenv('APP_ENV');
    $configuration = parse_ini_file(__DIR__ . "/config.ini", true);
    $debugLevel = $configuration[$environment]["debugLevel"];
    $log = new Logger($debugLevel);
    $log->setChannel(basename(__FILE__));

    $loopUpCli   = new NemLookUpCli(new Logger($debugLevel));

    echo "\n$printSeparator";
    do{
        $oioublFilepath = readline("* Filepath to input OIOUBL document('q' to quit): ");
        preg_match('/^.+\.xml$/i', $oioublFilepath, $isInputFilePathValid);
    }while(!isset($isInputFilePathValid[0]) && (trim(strtolower($oioublFilepath)) !== "q"));
    if(trim(strtolower($oioublFilepath)) == "q") {
        exit("$printSeparator\nGoodbye!\n");
    }
    echo "$printSeparator\n";

    $xmlString              = file_get_contents($oioublFilepath);
    $oxalisWrapper          = new OxalisWrapper($xmlString, new Logger($debugLevel));
    $endpoints              = $oxalisWrapper->getEndpoints();
    $log->log("Endpoint Found:\t{$endpoints["Receiver"]}");
    $endpoints["Receiver"]  = preg_replace('/0184:DK/', '0184:', $endpoints["Receiver"]);
    $loopUpCli->lookupEndpoint($endpoints["Receiver"], $httpCode);

    $log->log("httpCode:\t$httpCode");

    $xml = simplexml_load_string($xmlString);
    $profileID              = (string)$xml->children('cbc', true)->ProfileID;
    $log->log("In document profileID:\t$profileID");
    $compatibleProfileIDs   = $loopUpCli->getProfileNames($endpoints["Receiver"], NetworkType::NemHandel_AS4);
    $log->log("Compatible ProfileIDs on Endpoint:");
    $log->log($compatibleProfileIDs);

    if(!in_array($profileID, $compatibleProfileIDs))
    {
        if(in_array(ProfileID::procurement_BilSimR_1_0, $compatibleProfileIDs))
            $xml->children('cbc', true)->ProfileID = ProfileID::procurement_BilSimR_1_0;
        elseif(in_array(ProfileID::procurement_BilSim_1_0, $compatibleProfileIDs))
            $xml->children('cbc', true)->ProfileID = ProfileID::procurement_BilSim_1_0;
        $xmlString = $xml->asXML();
    }

    $oioublFilepath = preg_replace('/(\.xml$)/', '-with-correct-profileId$1', $oioublFilepath);
    file_put_contents($oioublFilepath, $xmlString);


}catch (Exception $e)
{
}