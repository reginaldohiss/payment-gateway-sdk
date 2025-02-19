<?php

namespace PaymentGateway\Utils;

class Logger
{
    /**
     * @param string $message
     * @param string $level
     * @return void
     */
    public static function log(string $message, string $level = "INFO")
    {
        $logFile = __DIR__ . '/../../logs/payment_gateway.log';
        $logMessage = date('[Y-m-d H:i:s]') . " [{$level}] " . $message . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * @param string $message
     * @return void
     */
    public static function error(string $message)
    {
        self::log($message, "ERROR");
    }
}
