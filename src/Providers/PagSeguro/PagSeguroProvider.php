<?php

namespace PaymentGateway\Providers\PagSeguro;

use PaymentGateway\Contracts\PaymentProviderInterface;
use PaymentGateway\Utils\HttpClient;
use PaymentGateway\Config\Environment;
use PaymentGateway\TransactionDetails;

class PagSeguroProvider implements PaymentProviderInterface
{
    /**
     * @var string
     */
    private string $apiUrl;
    /**
     * @var string|array|false
     */
    private string $apiKey;
    /**
     * @var Environment
     */
    private Environment $environment;

    /**
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
        $this->apiKey = getenv('PAGSEGURO_API_KEY') ?: 'sua-chave-aqui';

        $this->apiUrl = $environment->isProduction() ?
            "https://api.pagseguro.com" :
            "https://sandbox.api.pagseguro.com";
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws \Exception
     */
    public function payWithPix(array $data)
    {
        $url = "{$this->apiUrl}/v2/charges";
        $headers = ["Authorization: Bearer {$this->apiKey}", "Content-Type: application/json"];

        $response = HttpClient::post($url, $headers, [
            "paymentMethod" => "pix",
            "amount" => $data['amount']
        ]);

        return $response['qrCode']['content'] ?? "Erro ao gerar QR Code Pix no PagSeguro.";
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws \Exception
     */
    public function payWithBoleto(array $data)
    {
        $url = "{$this->apiUrl}/v2/charges";
        $headers = ["Authorization: Bearer {$this->apiKey}", "Content-Type: application/json"];

        $response = HttpClient::post($url, $headers, [
            "paymentMethod" => "boleto",
            "amount" => $data['amount'],
            "customer" => $data['customer']
        ]);

        return $response['boleto']['pdfUrl'] ?? "Erro ao gerar boleto no PagSeguro.";
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws \Exception
     */
    public function payWithCreditCard(array $data)
    {
        $url = "{$this->apiUrl}/v2/charges";
        $headers = ["Authorization: Bearer {$this->apiKey}", "Content-Type: application/json"];

        $response = HttpClient::post($url, $headers, [
            "paymentMethod" => "credit_card",
            "amount" => $data['amount'],
            "card" => $data['card']
        ]);

        return $response['transactionId'] ?? "Erro ao processar pagamento com cartão no PagSeguro.";
    }

    /**
     * @param string $transactionId
     * @return TransactionDetails
     * @throws \Exception
     */
    public function getTransactionDetails(string $transactionId)
    {
        $url = "{$this->apiUrl}/v2/transactions/{$transactionId}";
        $headers = ["Authorization: Bearer {$this->apiKey}"];

        $response = HttpClient::get($url, $headers);

        return new TransactionDetails(
            $transactionId,
            $response['status'],
            $response['amount'],
            $response['paymentMethod'],
            $response['createdAt']
        );
    }
}
