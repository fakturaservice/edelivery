<?php

namespace Fakturaservice\Edelivery;

use DOMDocument;
use Exception;
use XSLTProcessor;


class Converter2
{
    private string $_xsltFilePath;
    private ?\Saxon\SaxonProcessor $_saxonProc;

    public function __construct(string $xsltFilePath) {

        $this->_xsltFilePath = $xsltFilePath;
        $this->_saxonProc   = (in_array("Saxon/C", get_loaded_extensions()))? new \Saxon\SaxonProcessor():null;
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
            return "";

        $xsltProc   = $this->_saxonProc->newXsltProcessor();

        // LOAD XSLT SCRIPT
        $xsltProc->compileFromFile($this->_xsltFilePath);

        $xsltProc->setSourceFromFile($oioublXmlPath);

        // RUN TRANSFORMATION
        $xhtml = $xsltProc->transformToString();

        if(($errCnt = $xsltProc->getExceptionCount()) > 0)
        {
            $errorStr   = "\n<b>(HTML) XSD Error:</b></br>\n----------------</br>\n</br>\n";
            for($i = 0; $i < $errCnt; $i++)
            {
                $errorStr .= "{$xsltProc->getErrorCode($i)}:{$xsltProc->getErrorMessage($i)}" ;
            }
            // RELEASE RESOURCES
            $xsltProc->clearParameters();
            $xsltProc->clearProperties();

            unset($xsltProc);

            return $errorStr;
        }

        // RELEASE RESOURCES
        $xsltProc->clearParameters();
        $xsltProc->clearProperties();

        unset($xsltProc);

        return $xhtml;

    }
}

// Usage example:
// $converter = new Converter2('/path/to/your/xslt/file.xslt');
// $oioublXml = file_get_contents('/path/to/your/oioubl/document.xml');
// $peppolBis3Xml = $converter->convert($oioublXml);
// echo $peppolBis3Xml;