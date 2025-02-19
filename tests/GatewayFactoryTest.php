<?php

use PHPUnit\Framework\TestCase;
use PaymentGateway\GatewayFactory;
use PaymentGateway\Providers\Stripe\StripeProvider;
use PaymentGateway\Exceptions\ProviderNotFoundException;

class GatewayFactoryTest extends TestCase
{
    /**
     * @return void
     * @throws ProviderNotFoundException
     */
    public function testCanCreateStripeProvider()
    {
        $provider = GatewayFactory::create('stripe');
        $this->assertInstanceOf(StripeProvider::class, $provider);
    }

    /**
     * @return void
     * @throws ProviderNotFoundException
     */
    public function testThrowsExceptionForInvalidProvider()
    {
        $this->expectException(ProviderNotFoundException::class);
        GatewayFactory::create('invalidProvider');
    }
}
