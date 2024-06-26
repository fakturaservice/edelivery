<?php


namespace Fakturaservice\Edelivery\util;

use Exception;


class Logger implements LoggerInterface
{
    const LOG_OK        = "\033[32mOK\033[0m";
    const LOG_WARN      = "\033[33mWARN\033[0m";
    const LOG_ERR       = "\033[31mERROR\033[0m";

    /** @var int Most verbose */
    const LV_1  = 1;
    /** @var int  Verbose */
    const LV_2  = 2;
    /** @var int Most silent */
    const LV_3  = 3;

    const STR_LEN_CH_NAME   = 15;

    private string $_channel;
    private int $_debugLevel;
    private ?array $_errorMsg;
    /**
     * @var true
     */
    private bool $_success;
    private bool $_useExceptions;
    /**
     * @var array|false|string
     */
    private $_environment;

    /**
     * @throws Exception
     */
    public function __construct(int $debugLevel=0, bool $useExceptions=false)
    {
        $this->_environment     = getenv('APP_ENV');
        $this->_debugLevel      = $debugLevel;
        $this->_channel         = "";
        $this->_errorMsg        = null;
        $this->_success         = true;
        $this->_useExceptions   = $useExceptions;
    }

    /**
     * @throws Exception
     */
    public function setChannel(string $channel): void
    {
        $this->_channel     = str_pad(substr($channel, 0, self::STR_LEN_CH_NAME), self::STR_LEN_CH_NAME);
        $this->log("Initiating debug log channel: $this->_channel");
    }
    public function getChannel(): string
    {
        return $this->_channel;
    }
    /**
     * @throws Exception
     */
    public function setLogLevel(int $debugLevel): void
    {
        $this->_debugLevel  = $debugLevel;
        $this->log("Initiating debug log level: $debugLevel");
    }
    public function getLogLevel(): int
    {
        return $this->_debugLevel;
    }
    public function success(): bool
    {
        return $this->_success;
    }
    public function getErrorMsg() : string
    {
        return (isset($this->_errorMsg))?implode("", $this->_errorMsg):"";
    }

    /**
     * @throws Exception
     */
    public function log($str, int $level=self::LV_3, string $err=self::LOG_OK)
    {
        if($this->_environment == "dev")
        {
            $backtrace  = debug_backtrace();
            $info       = str_pad(
                (substr(basename($backtrace[0]["file"], ".php"), 0, self::STR_LEN_CH_NAME) . ":". $backtrace[0]["line"]),
                self::STR_LEN_CH_NAME);
            $info       = "\t[$info]";
        }
        else
            $info = "";
        if(is_array($str))
            $str = var_export($str, true) . "\n";
        else
            $str = date("d-m-Y H:i:s") . "\t[$this->_channel]$info\t[$err]\t $str\n";
        if($this->_debugLevel >= $level)
            echo $str;
        if($err != self::LOG_OK)
            $this->_errorMsg[] = $str;
        if($err == self::LOG_ERR)
        {
            $this->_success = false;
            if($this->_useExceptions)
                throw new Exception($str);
        }
    }

}