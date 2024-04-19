<?php
namespace example;

use Exception;

require_once __DIR__ . '/EDelivery.php';

//putenv('APP_ENV=prod');
putenv('APP_ENV=dev');

try
{
    $environment    = getenv('APP_ENV');
    $configuration  = parse_ini_file(__DIR__ . "/config.ini", true);
    $debugLevel     = $configuration[$environment]["debugLevel"];

    $eDelivery  = new EDelivery($debugLevel);
    $eDelivery->setupOxalisCli(
        $configuration[$environment]["peppolAS4Url"],
        $configuration[$environment]["peppolAS4Usr"],
        $configuration[$environment]["peppolAS4PsW"],
        $configuration[$environment]["peppolAS4DBHst"],
        $configuration[$environment]["peppolAS4DBUsr"],
        $configuration[$environment]["peppolAS4DBPsW"],
        $configuration[$environment]["peppolAS4DBName"]
    );

//    $eDelivery->sendTSR(
//        $configuration[$environment]["peppolReporterEndpointId"],
//        $configuration[$environment]["peppolReporterCertCN"],
//        "2024-04-01");
    $eDelivery->sendEUSR(
        $configuration[$environment]["peppolReporterEndpointId"],
        $configuration[$environment]["peppolReporterCertCN"],
        "2024-03-01");

//    $eDelivery->distributeEDocuments();
//    $eDelivery->send(__DIR__ . "/testInvoicePeppolDoc.xml");

} catch (Exception $e)
{
}