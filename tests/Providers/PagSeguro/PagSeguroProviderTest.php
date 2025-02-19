<?php

use PHPUnit\Framework\TestCase;
use PaymentGateway\Providers\PagSeguro\PagSeguroProvider;
use PaymentGateway\Config\Environment;

class PagSeguroProviderTest extends TestCase
{
    /**
     * @var PagSeguroProvider
     */
    private $provider;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $environment = new Environment(Environment::SANDBOX);
        $this->provider = new PagSeguroProvider($environment);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPayWithPix()
    {
        $response = $this->provider->payWithPix(['amount' => 100]);
        $this->assertNotEmpty($response);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPayWithBoleto()
    {
        $response = $this->provider->payWithBoleto(['amount' => 100]);
        $this->assertNotEmpty($response);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testPayWithCreditCard()
    {
        $response = $this->provider->payWithCreditCard(['amount' => 100]);
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
