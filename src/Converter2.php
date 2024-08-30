<?php

namespace Fakturaservice\Edelivery;

use Exception;
use Fakturaservice\Edelivery\util\Logger;
use Fakturaservice\Edelivery\util\LoggerInterface;

class Converter2
{
    private string $_xsltFilePath;
    private string $_saxonApiUrl;
    private string $_className;
    private LoggerInterface $_log;

    public function __construct(
        LoggerInterface $logger,
        string $saxonApiUrl,
        string $xsltFilePath= __DIR__ . "/resources/XSLT/OIOUBL-21_2_PEPPOL-BIS3.xslt")
    {
        $this->_className       = basename(str_replace('\\', '/', get_called_class()));
        $this->_log             = $logger;
        $this->_log->setChannel($this->_className);
        $this->_xsltFilePath    = $xsltFilePath;
        $this->_saxonApiUrl     = $saxonApiUrl;

        preg_match(
            '/\b((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/',
            $this->_saxonApiUrl,
            $validUrl);
        if(empty($validUrl))
            $this->_log->log("Saxon API Url('$this->_saxonApiUrl') Invalid", Logger::LV_1, Logger::LOG_ERR);
    }
    public function __destruct()
    {
    }

    /**
     * @throws Exception
     */
    public function convert($oioublXmlPath): string
    {
        $httpResponse = 0;
        $saxonVersion   = $this->saxon("", $httpResponse);
        if($httpResponse != 200)
            $this->_log->log("SaxonApi service failed. Http response code: $httpResponse", Logger::LV_1, Logger::LOG_ERR);
        preg_match('/saxon/i', $saxonVersion, $saxonHasVersion);
        if(empty($saxonHasVersion))
        {
            $this->_log->log("Saxon/C is not installed", Logger::LV_1, Logger::LOG_ERR);
            return "";
        }
        $this->_log->log("Saxon/C version: $saxonVersion");

        $httpResponse = 0;
        $xhtml  = $this->saxon("transform/", $httpResponse, file_get_contents($oioublXmlPath), file_get_contents($this->_xsltFilePath));
        if($httpResponse != 200)
            $this->_log->log("SaxonApi service failed. Http response code: $httpResponse", Logger::LV_1, Logger::LOG_ERR);
        else
            $this->_log->log("Succeed converting document");
        return $xhtml;
    }

    private function saxon(
        string $api,
        int &$httpResponse,
        string $inputXml=null,
        string $outputFormat=null
    ): string
    {
        $ch = curl_init("$this->_saxonApiUrl/$api");
        switch($api)
        {
            case "transform/":
            {
                $postArray  = [
                    "xml"   => base64_encode($inputXml),
                    "xslt"  => base64_encode($outputFormat)
                ];

                $post = http_build_query($postArray);

                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                break;
            }
            case "":
            {
                curl_setopt($ch, CURLOPT_ENCODING, '');
                curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            }
        }
        $curlResponse   = curl_exec($ch);
        $httpResponse   = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        return trim($curlResponse);
    }
}

// Usage example:
// $converter = new Converter2('/path/to/your/xslt/file.xslt');
// $oioublXml = file_get_contents('/path/to/your/oioubl/document.xml');
// $peppolBis3Xml = $converter->convert($oioublXml);
// echo $peppolBis3Xml;