<?php

namespace PaymentGateway\Contracts;

interface PaymentProviderInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function payWithPix(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function payWithBoleto(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function payWithCreditCard(array $data);

    /**
     * @param string $transactionId
     * @return mixed
     */
    public function getTransactionDetails(string $transactionId);
}
