<?php
namespace example;

require_once __DIR__ . '/EDelivery.php';

try
{
    $debugLevel = 3;
    $eDelivery  = new EDelivery($debugLevel);
//    die("Test done\n");
    $eDelivery->send(__DIR__ . "/testInvoicePeppolDoc.xml");

} catch (\Exception $e)
{
}