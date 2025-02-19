<?php

use PHPUnit\Framework\TestCase;
use PaymentGateway\Providers\Cielo\CieloProvider;
use PaymentGateway\Config\Environment;

class CieloProviderTest extends TestCase
{
    /**
     * @var CieloProvider
     */
    private $provider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $environment = new Environment(Environment::SANDBOX);
        $this->provider = new CieloProvider($environment);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPayWithBoleto()
    {
        $response = $this->provider->payWithBoleto([
            "amount" => 150,
            "customer" => [
                "name" => "Maria Santos",
                "document" => "98765432100"
            ]
        ]);

        $this->assertNotEmpty($response);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPayWithCreditCard()
    {
        $response = $this->provider->payWithCreditCard([
            "amount" => 200,
            "card" => [
                "number" => "4111111111111111",
                "holder" => "Carlos Souza",
                "expiry" => "12/28",
                "cvv" => "123",
                "brand" => "Visa"
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
