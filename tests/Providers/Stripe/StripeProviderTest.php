<?php

use PHPUnit\Framework\TestCase;
use PaymentGateway\Providers\Stripe\StripeProvider;
use PaymentGateway\Config\Environment;

class StripeProviderTest extends TestCase
{
    /**
     * @var StripeProvider
     */
    private $provider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $environment = new Environment(Environment::SANDBOX);
        $this->provider = new StripeProvider($environment);
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
