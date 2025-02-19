<?php

namespace PaymentGateway;

use PaymentGateway\Contracts\PaymentProviderInterface;

class PaymentGateway
{
    /**
     * @var PaymentProviderInterface
     */
    protected PaymentProviderInterface $provider;

    /**
     * @param PaymentProviderInterface $provider
     */
    public function __construct(PaymentProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function payWithPix(array $data)
    {
        return $this->provider->payWithPix($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function payWithBoleto(array $data)
    {
        return $this->provider->payWithBoleto($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function payWithCreditCard(array $data)
    {
        return $this->provider->payWithCreditCard($data);
    }

    /**
     * @param string $transactionId
     * @return mixed
     */
    public function getTransactionDetails(string $transactionId)
    {
        return $this->provider->getTransactionDetails($transactionId);
    }
}
