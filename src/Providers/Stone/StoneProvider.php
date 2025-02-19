<?php

namespace PaymentGateway\Providers\Stone;

use PaymentGateway\Contracts\PaymentProviderInterface;
use PaymentGateway\Utils\HttpClient;
use PaymentGateway\Config\Environment;
use PaymentGateway\TransactionDetails;

class StoneProvider implements PaymentProviderInterface
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
        $this->apiKey = getenv('STONE_API_KEY') ?: 'sua-chave-aqui';

        $this->apiUrl = $environment->isProduction() ?
            "https://api.stone.com.br" :
            "https://sandbox.api.stone.com.br";
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws \Exception
     */
    public function payWithPix(array $data)
    {
        $url = "{$this->apiUrl}/v1/pix/transactions";
        $headers = ["Authorization: Bearer {$this->apiKey}", "Content-Type: application/json"];

        $response = HttpClient::post($url, $headers, [
            "amount" => $data['amount'],
            "payer" => [
                "document" => $data['payer']['document'],
                "name" => $data['payer']['name']
            ]
        ]);

        return $response['pix_qr_code'] ?? "Erro ao gerar QR Code Pix na Stone.";
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws \Exception
     */
    public function payWithBoleto(array $data)
    {
        $url = "{$this->apiUrl}/v1/boletos";
        $headers = ["Authorization: Bearer {$this->apiKey}", "Content-Type: application/json"];

        $response = HttpClient::post($url, $headers, [
            "amount" => $data['amount'],
            "payer" => [
                "document" => $data['payer']['document'],
                "name" => $data['payer']['name']
            ],
            "due_date" => date("Y-m-d", strtotime("+3 days"))
        ]);

        return $response['boleto_pdf_url'] ?? "Erro ao gerar boleto na Stone.";
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws \Exception
     */
    public function payWithCreditCard(array $data)
    {
        $url = "{$this->apiUrl}/v1/card_transactions";
        $headers = ["Authorization: Bearer {$this->apiKey}", "Content-Type: application/json"];

        $response = HttpClient::post($url, $headers, [
            "amount" => $data['amount'],
            "card" => [
                "number" => $data['card']['number'],
                "holder" => $data['card']['holder'],
                "expiry" => $data['card']['expiry'],
                "cvv" => $data['card']['cvv']
            ]
        ]);

        return $response['transaction_id'] ?? "Erro ao processar pagamento com cartão na Stone.";
    }

    /**
     * @param string $transactionId
     * @return TransactionDetails
     * @throws \Exception
     */
    public function getTransactionDetails(string $transactionId)
    {
        $url = "{$this->apiUrl}/v1/transactions/{$transactionId}";
        $headers = ["Authorization: Bearer {$this->apiKey}"];

        $response = HttpClient::get($url, $headers);

        return new TransactionDetails(
            $transactionId,
            $response['status'],
            $response['amount'],
            $response['payment_method'],
            $response['created_at']
        );
    }
}
