<?php

namespace PaymentGateway;

class TransactionDetails
{
    /**
     * @var string
     */
    public string $transactionId;
    /**
     * @var string
     */
    public string $status;
    /**
     * @var float
     */
    public float $amount;
    /**
     * @var string
     */
    public string $paymentMethod;
    /**
     * @var string
     */
    public string $createdAt;

    /**
     * @param string $transactionId
     * @param string $status
     * @param float $amount
     * @param string $paymentMethod
     * @param string $createdAt
     */
    public function __construct(string $transactionId, string $status, float $amount, string $paymentMethod, string $createdAt)
    {
        $this->transactionId = $transactionId;
        $this->status = $status;
        $this->amount = $amount;
        $this->paymentMethod = $paymentMethod;
        $this->createdAt = $createdAt;
    }
}
