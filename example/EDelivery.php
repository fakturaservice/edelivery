<?php

namespace example;

require_once __DIR__ . '/../vendor/autoload.php';

use Exception;
use Fakturaservice\Edelivery\{
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

//        if(!$this->_nemLookUpCli->lookupEndpoint($endpoint, $httpCode))
//            $this->_log->log("No eDelivery server found on endpoint: '$endpoint'", Logger::LV_1, Logger::LOG_ERR);

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

//        $networkTypes = $this->_nemLookUpCli->getNetworkTypeIds();
        return true;
    }
}