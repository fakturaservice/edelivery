<?php

namespace example;

require_once __DIR__ . '/../vendor/autoload.php';

use Exception;
use Fakturaservice\Edelivery\{
    OIOUBL\NetworkType,
    OxalisCli,
    NemLookUpCli,
    OxalisWrapper,
    util\Logger
};

class EDelivery
{
    private NemLookUpCli $_nemLookUpCli;
    private Logger $_log;
    private string $_className;
    private int $_debugLevel;
    private ?OxalisCli $_oxalisCli;


    /**
     * @throws Exception
     */
    public function __construct(int $debugLevel=0)
    {
        $this->_className       = basename(str_replace('\\', '/', get_called_class()));
        $this->_debugLevel      = $debugLevel;
        $this->_log             = new Logger($this->_debugLevel);
        $this->_log->setChannel($this->_className);
        $this->_nemLookUpCli    = new NemLookUpCli(new Logger($this->_debugLevel));
        $this->_oxalisCli       = null;
    }

    /**
     * @throws Exception
     */
    public function setupOxalisCli(
        string $apiUrl, string $apiUserName, string $apiPassWord,
        string $dbUrl,  string $dbUserName, string $dbPassWord, string $db
    )
    {
        $this->_oxalisCli   = new OxalisCli(
            $apiUrl,
            $apiUserName,
            $apiPassWord,
            new Logger($this->_debugLevel)
        );
        $this->_oxalisCli->connectOxalisDB(
            $dbUrl,
            $dbUserName,
            $dbPassWord,
            $db
        );

    }

    /**
     * @throws Exception
     */
    public function distributeEDocuments(int $networkType=NetworkType::PEPPOL_AS4)
    {
        $eDocs      = $this->_oxalisCli->inbox();
        $this->_log->log("Number of documents in " . NetworkType::getName($networkType) . " inbox: " . count($eDocs));

        foreach ($eDocs as $id => $eDoc)
        {
            $wrapper            = new OxalisWrapper($eDoc["payload"], new Logger($this->_debugLevel));
            $endpoint           = $wrapper->getEndpoints(OxalisWrapper::TRANSACTION_PARTICIPANT_RECEIVER);
            $endpoint           = preg_replace('/0184:DK/', '0184:', $endpoint);
            $accountReceiver    = $this->_oxalisCli->getAccountReceiver($endpoint);
            if(empty($accountReceiver))
            {
                $this->_log->log("Account receiver in ID '$id' was empty");
//                $this->_oxalisCli->markIncomingAsRead($id);
                continue;
            }
            $this->_log->log("Account receiver in ID '$id' was {$accountReceiver["created_by"]}");

//            $this->_oxalisCli->markIncomingAsRead($id);

        }
    }

    /**
     * @throws Exception
     */
    public function sendTSR()
    {
        $this->_log->log("Calling sendTSR");
//        $TSReport = $this->_oxalisCli->createTSR("2023-11-01", "PDK000605");
//        $TSReport = $this->_oxalisCli->createTSR("2023-12-01", "PDK000605");
//        $TSReport = $this->_oxalisCli->createTSR("2024-01-01", "PDK000605");
//        $TSReport = $this->_oxalisCli->createTSR("2024-02-01", "PDK000605");
        $TSReport = $this->_oxalisCli->createTSR("2024-03-01", "PDK000605");
//        $TSReport = $this->_oxalisCli->createTSR("2024-04-01", "PDK000605");
//        $this->_oxalisCli->outbox($TSReport, NetworkType::PEPPOL_AS4);

    }

    /**
     * @throws Exception
     */
    public function send(string $eDocXml):bool
    {
        $wrapper    = new OxalisWrapper($eDocXml, new Logger($this->_debugLevel));
        $endpoint   = $wrapper->getEndpoints(OxalisWrapper::TRANSACTION_PARTICIPANT_RECEIVER);
        $this->_log->log("Got endpoint from eDoc: $endpoint");
        $endpoint   = preg_replace('/0184:DK/', '0184:', $endpoint);

        $isPeppol   = $this->_nemLookUpCli->lookupEndpointPeppol($endpoint, $httpCode);
        $isNHR      = $this->_nemLookUpCli->lookupEndpoint($endpoint, $httpCode);

        if($isPeppol)
            $this->_log->log("Endpoint is registered on PEPPOL network");
        elseif($isNHR)
            $this->_log->log("Endpoint is registered on NEMHANDEL network");
        else {
            $this->_log->log("No eDelivery server found on endpoint: '$endpoint'", Logger::LV_1, Logger::LOG_ERR);
            return false;
        }

        return true;
    }
}