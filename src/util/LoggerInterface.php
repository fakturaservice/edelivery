<?php

namespace Fakturaservice\Edelivery\util;

interface LoggerInterface
{
    public function getChannel(): string;
    public function setChannel(string $channel): void;

    public function getLogLevel(): int;
    public function setLogLevel(int $debugLevel): void;

    public function success(): bool;
    public function getErrorMsg() : string;

    public function log($str, int $level, string $err);
}