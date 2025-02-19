<?php

use PHPUnit\Framework\TestCase;
use PaymentGateway\PaymentGateway;
use PaymentGateway\Providers\Stripe\StripeProvider;
use PaymentGateway\Config\Environment;

class PaymentGatewayTest extends TestCase
{
    /**
     * @var PaymentGateway
     */
    private $gateway;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $environment = new Environment(Environment::SANDBOX);
        $provider = new StripeProvider($environment);
        $this->gateway = new PaymentGateway($provider);
    }

    /**
     * @return void
     */
    public function testCanMakePaymentWithPix()
    {
        $response = $this->gateway->payWithPix([
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
     */
    public function testCanMakePaymentWithCreditCard()
    {
        $response = $this->gateway->payWithCreditCard([
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
     */
    public function testCanRetrieveTransactionDetails()
    {
        $transactionId = "123ABC";
        $response = $this->gateway->getTransactionDetails($transactionId);
        $this->assertNotEmpty($response);
    }
}
