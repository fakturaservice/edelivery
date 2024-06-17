<?php

namespace example;

require_once __DIR__ . '/../src/util/autoload_helper.php';
require_once findComposerAutoload();

use Exception;
use Fakturaservice\Edelivery\{
    OIOUBL\NetworkType,
    OxalisReport,
    OxalisWrapper,
    util\Logger
};

class EReports
{
    private Logger $_log;
    private string $_className;
    private int $_debugLevel;
    private ?OxalisReport $_oxalisReport;


    /**
     * @throws Exception
     */
    public function __construct(int $debugLevel=0)
    {
        $this->_className       = basename(str_replace('\\', '/', get_called_class()));
        $this->_debugLevel      = $debugLevel;
        $this->_log             = new Logger($this->_debugLevel);
        $this->_log->setChannel($this->_className);
        $this->_oxalisReport       = null;
    }

    /**
     * @throws Exception
     */
    public function setupOxalisCli(
        string $apiUrl, string $apiUserName, string $apiPassWord,
        string $dbUrl,  string $dbUserName, string $dbPassWord, string $db
    )
    {
        $this->_oxalisReport   = new OxalisReport(
            $apiUrl,
            $apiUserName,
            $apiPassWord,
            new Logger($this->_debugLevel)
        );
        $this->_oxalisReport->connectOxalisDB(
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
        $TSReport           = $this->_oxalisReport->createTSR($startDate, $reporterCertCN);
        $oxalisWrapper      = new OxalisWrapper($TSReport, new Logger($this->_log->getLogLevel()));
        $TSReportWrapped    = $oxalisWrapper->wrapSBD(NetworkType::PEPPOL_AS4, $reporterEndpointId, true);
        $this->_log->log("Wrapped TSReport:\n$TSReportWrapped", Logger::LV_1);

        if(getenv('APP_ENV') == "prod")
            $this->_oxalisReport->outbox($TSReportWrapped, NetworkType::PEPPOL_AS4);
    }

    /**
     * @throws Exception
     */
    public function sendEUSR($reporterEndpointId, $reporterCertCN, $startDate)
    {
        $this->_log->log("Calling sendEUSR:");
        $EUSReport          = $this->_oxalisReport->createEUSR($startDate, $reporterCertCN);
        $oxalisWrapper      = new OxalisWrapper($EUSReport, new Logger($this->_log->getLogLevel()));
        $EUSReportWrapped   = $oxalisWrapper->wrapSBD(NetworkType::PEPPOL_AS4, $reporterEndpointId, true);
        $this->_log->log("Wrapped EUSReport:\n$EUSReportWrapped", Logger::LV_1);

        if(getenv('APP_ENV') == "prod")
            $this->_oxalisReport->outbox($EUSReportWrapped, NetworkType::PEPPOL_AS4);
    }

}