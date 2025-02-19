<?php

use PHPUnit\Framework\TestCase;
use PaymentGateway\Providers\Itau\ItauProvider;
use PaymentGateway\Config\Environment;

class ItauProviderTest extends TestCase
{
    /**
     * @var ItauProvider
     */
    private $provider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $environment = new Environment(Environment::SANDBOX);
        $this->provider = new ItauProvider($environment);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPayWithPix()
    {
        $response = $this->provider->payWithPix([
            "amount" => 100,
            "payer" => [
                "name" => "João Silva",
                "document" => "12345678909"
            ]
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
