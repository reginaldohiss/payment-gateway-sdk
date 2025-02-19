<?php

use PHPUnit\Framework\TestCase;
use PaymentGateway\Providers\BancoDoBrasil\BancoDoBrasilProvider;
use PaymentGateway\Config\Environment;

class BancoDoBrasilProviderTest extends TestCase
{
    /**
     * @var BancoDoBrasilProvider
     */
    private $provider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $environment = new Environment(Environment::SANDBOX);
        $this->provider = new BancoDoBrasilProvider($environment);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPayWithPix()
    {
        $response = $this->provider->payWithPix([
            "amount" => 100,
            "pixKey" => "12345678909"
        ]);

        $this->assertNotEmpty($response);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGetTransactionDetails()
    {
        $transactionId = "123ABC";
        $response = $this->provider->getTransactionDetails($transactionId);
        $this->assertNotEmpty($response);
    }
}
