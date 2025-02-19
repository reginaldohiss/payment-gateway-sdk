<?php

namespace PaymentGateway\Providers\Stripe;

use PaymentGateway\Contracts\PaymentProviderInterface;
use PaymentGateway\Utils\HttpClient;
use PaymentGateway\Config\Environment;
use PaymentGateway\TransactionDetails;
use Exception;

class StripeProvider implements PaymentProviderInterface
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
        $this->apiKey = getenv('STRIPE_API_KEY') ?: 'sua-chave-aqui';

        $this->apiUrl = "https://api.stripe.com/v1";
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws Exception
     */
    public function payWithPix(array $data)
    {
        $url = "{$this->apiUrl}/payment_intents";
        $headers = ["Authorization: Bearer {$this->apiKey}", "Content-Type: application/x-www-form-urlencoded"];

        $response = HttpClient::post($url, $headers, [
            "amount" => $data['amount'] * 100,
            "currency" => "brl",
            "payment_method_types[]" => "pix"
        ]);

        return $response['id'] ?? "Erro ao processar pagamento Pix no Stripe.";
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function payWithBoleto(array $data)
    {
        throw new Exception("O Stripe não oferece boleto.");
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws Exception
     */
    public function payWithCreditCard(array $data)
    {
        $url = "{$this->apiUrl}/payment_intents";
        $headers = ["Authorization: Bearer {$this->apiKey}", "Content-Type: application/x-www-form-urlencoded"];

        $response = HttpClient::post($url, $headers, [
            "amount" => $data['amount'] * 100,
            "currency" => "brl",
            "payment_method_types[]" => "card"
        ]);

        return $response['id'] ?? "Erro ao processar pagamento com cartão no Stripe.";
    }

    /**
     * @param string $transactionId
     * @return TransactionDetails
     * @throws Exception
     */
    public function getTransactionDetails(string $transactionId)
    {
        $url = "{$this->apiUrl}/payment_intents/{$transactionId}";
        $headers = ["Authorization: Bearer {$this->apiKey}"];

        $response = HttpClient::get($url, $headers);

        return new TransactionDetails(
            $transactionId,
            $response['status'],
            $response['amount'] / 100,
            "credit_card",
            date("Y-m-d H:i:s")
        );
    }
}
