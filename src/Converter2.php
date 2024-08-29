<?php

namespace Fakturaservice\Edelivery;

use Exception;
use Fakturaservice\Edelivery\util\Logger;
use Fakturaservice\Edelivery\util\LoggerInterface;

class Converter2
{
    private string $_xsltFilePath;
    private string $_className;
    private LoggerInterface $_log;

    public function __construct(LoggerInterface $logger, string $xsltFilePath)
    {
        $this->_className       = basename(str_replace('\\', '/', get_called_class()));
        $this->_log             = $logger;
        $this->_log->setChannel($this->_className);
        $this->_xsltFilePath    = $xsltFilePath;
    }
    public function __destruct()
    {
    }

    /**
     * @throws Exception
     */
    public function convert($oioublXmlPath): string
    {
        $saxonVersion   = $this->saxon("");
        preg_match('/saxon/i', $saxonVersion, $saxonHasVersion);
        if(empty($saxonHasVersion))
        {
            $this->_log->log("Saxon/C is not installed", Logger::LV_3, Logger::LOG_ERR);
            return "";
        }

        $this->_log->log("Saxon/C version: $saxonVersion");
        $xhtml      = $this->saxon("transform/", file_get_contents($oioublXmlPath), file_get_contents($this->_xsltFilePath));
        $this->_log->log("Succeed converting document");
        return $xhtml;
    }

    private function saxon(
        string $api,
        string $inputXml=null,
        string $outputFormat=null
    ): string
    {
        $ch = curl_init("139.162.133.199/saxon_api/$api");
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

        $curlResponse = curl_exec($ch);
        curl_close($ch);

        return trim($curlResponse);
    }
}

// Usage example:
// $converter = new Converter2('/path/to/your/xslt/file.xslt');
// $oioublXml = file_get_contents('/path/to/your/oioubl/document.xml');
// $peppolBis3Xml = $converter->convert($oioublXml);
// echo $peppolBis3Xml;