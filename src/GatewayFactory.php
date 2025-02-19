<?php

namespace PaymentGateway;

use PaymentGateway\Providers\PagSeguro\PagSeguroProvider;
use PaymentGateway\Providers\Cielo\CieloProvider;
use PaymentGateway\Providers\Itau\ItauProvider;
use PaymentGateway\Providers\BancoDoBrasil\BancoDoBrasilProvider;
use PaymentGateway\Providers\Stone\StoneProvider;
use PaymentGateway\Providers\Stripe\StripeProvider;
use PaymentGateway\Contracts\PaymentProviderInterface;
use PaymentGateway\Exceptions\ProviderNotFoundException;
use PaymentGateway\Config\Environment;

class GatewayFactory
{
    /**
     * @param string $provider
     * @param string $environment
     * @return PaymentProviderInterface
     * @throws ProviderNotFoundException
     */
    public static function create(string $provider, string $environment = Environment::PRODUCTION): PaymentProviderInterface
    {
        $env = new Environment($environment);

        $providers = [
            'pagseguro' => new PagSeguroProvider($env),
            'cielo' => new CieloProvider($env),
            'itau' => new ItauProvider($env),
            'bancodobrasil' => new BancoDoBrasilProvider($env),
            'stone' => new StoneProvider($env),
            'stripe' => new StripeProvider($env),
        ];

        if (!isset($providers[strtolower($provider)])) {
            throw new ProviderNotFoundException("Provedor de pagamento '{$provider}' não encontrado.");
        }

        return $providers[strtolower($provider)];
    }
}
