<?php

namespace Fakturaservice\Edelivery;

use Exception;
use Fakturaservice\Edelivery\util\Logger;
use Fakturaservice\Edelivery\util\LoggerInterface;

class Converter2
{
    private string $_xsltFilePath;
    private ?\Saxon\SaxonProcessor $_saxonProc;
    private string $_className;
    private LoggerInterface $_log;

    public function __construct(LoggerInterface $logger, string $xsltFilePath)
    {
        $this->_className       = basename(str_replace('\\', '/', get_called_class()));
        $this->_log             = $logger;
        $this->_log->setChannel($this->_className);

        $this->_xsltFilePath    = $xsltFilePath;

        $this->_saxonProc       = array_intersect(['Saxon/C', 'saxonc'], array_map('strtolower', get_loaded_extensions()))
            ? new \Saxon\SaxonProcessor()
            : null;
    }
    public function __destruct()
    {
        if(isset($this->_saxonProc))
            unset($this->_saxonProc);
    }

    /**
     * @throws Exception
     */
    public function convert($oioublXmlPath): string
    {
        if(!isset($this->_saxonProc))
        {
            $this->_log->log("Saxon/C is not installed", Logger::LV_3, Logger::LOG_ERR);
            return "";
        }

        $this->_log->log("Saxon/C version: {$this->_saxonProc->version()}");

        $xsltProc   = $this->_saxonProc->newXslt30Processor();

        // LOAD XSLT SCRIPT
        $executable = $xsltProc->compileFromFile($this->_xsltFilePath);
        $xhtml     = $executable->transformFileToString($oioublXmlPath);
        if($xhtml == NULL)
        {
            if($executable->exceptionOccurred())
            {
                $errorStr   = "\n<b>(HTML) XSD Error:</b></br>\n----------------</br>\n</br>\n";
                $errCode    = $executable->getErrorCode();
                $errMessage = $executable->getErrorMessage();
                $errorStr   .= 'Expected error: Code='.$errCode.' Message='.$errMessage;
                $xsltProc->exceptionClear();

                $this->_log->log("Failed converting:\n$errorStr", Logger::LV_3, Logger::LOG_ERR);
                return "";
            }
        }

        $xsltProc->clearParameters();
        unset($xsltProc);

        $this->_log->log("Succeed converting document");

        return $xhtml;

    }
}

// Usage example:
// $converter = new Converter2('/path/to/your/xslt/file.xslt');
// $oioublXml = file_get_contents('/path/to/your/oioubl/document.xml');
// $peppolBis3Xml = $converter->convert($oioublXml);
// echo $peppolBis3Xml;