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
    public function sendTSR($reporterEndpointId, $reporterCertCN, $startDate)
    {
        $this->_log->log("Calling sendTSR:");
        $TSReport           = $this->_oxalisCli->createTSR($startDate, $reporterCertCN);
        $oxalisWrapper      = new OxalisWrapper($TSReport, new Logger($this->_log->getLogLevel()));
        $TSReportWrapped    = $oxalisWrapper->wrapSBD(NetworkType::PEPPOL_AS4, $reporterEndpointId);
        $this->_log->log("Wrapped TSR:\n$TSReportWrapped");

        if(getenv('APP_ENV') == "prod")
            $this->_oxalisCli->outbox($TSReportWrapped, NetworkType::PEPPOL_AS4);
    }

    /**
     * @throws Exception
     */
    public function sendEUSR($reporterEndpointId, $reporterCertCN, $startDate)
    {
        $this->_log->log("Calling sendEUSR:");
        $EUSReport          = $this->_oxalisCli->createEUSR($startDate, $reporterCertCN);
//        $oxalisWrapper      = new OxalisWrapper($EUSReport, new Logger($this->_log->getLogLevel()));
//        $EUSReportWrapped   = $oxalisWrapper->wrapSBD(NetworkType::PEPPOL_AS4, $reporterEndpointId);
//        $this->_log->log("Wrapped EUSReport:\n$EUSReportWrapped");
//
//        $this->_oxalisCli->outbox($EUSReportWrapped, NetworkType::PEPPOL_AS4);
    }

}