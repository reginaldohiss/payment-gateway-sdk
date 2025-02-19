<?php

namespace PaymentGateway\Config;

class Environment
{
    const PRODUCTION = 'production';
    const SANDBOX = 'sandbox';

    /**
     * @var string
     */
    private string $mode;

    /**
     * @param string $mode
     */
    public function __construct(string $mode = self::PRODUCTION)
    {
        $this->mode = in_array($mode, [self::PRODUCTION, self::SANDBOX]) ? $mode : self::PRODUCTION;
    }

    /**
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->mode === self::PRODUCTION;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }
}
