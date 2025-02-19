<?php

namespace PaymentGateway\Providers\Cielo;

use PaymentGateway\Contracts\PaymentProviderInterface;
use PaymentGateway\Utils\HttpClient;
use PaymentGateway\Config\Environment;
use PaymentGateway\TransactionDetails;
use Exception;

class CieloProvider implements PaymentProviderInterface
{
    /**
     * @var string
     */
    private string $apiUrl;
    /**
     * @var string|array|false
     */
    private string $merchantId;
    /**
     * @var string|array|false
     */
    private string $merchantKey;
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
        $this->merchantId = getenv('CIELO_MERCHANT_ID') ?: 'seu-merchant-id';
        $this->merchantKey = getenv('CIELO_MERCHANT_KEY') ?: 'sua-chave-aqui';

        $this->apiUrl = $environment->isProduction() ?
            "https://api.cieloecommerce.cielo.com.br" :
            "https://apisandbox.cieloecommerce.cielo.com.br";
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function payWithPix(array $data)
    {
        throw new Exception("A Cielo não oferece pagamento via Pix.");
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws Exception
     */
    public function payWithBoleto(array $data)
    {
        $url = "{$this->apiUrl}/1/sales/";
        $headers = [
            "MerchantId: {$this->merchantId}",
            "MerchantKey: {$this->merchantKey}",
            "Content-Type: application/json"
        ];

        $response = HttpClient::post($url, $headers, [
            "MerchantOrderId" => uniqid(),
            "Customer" => ["Name" => $data['customer']['name']],
            "Payment" => [
                "Type" => "Boleto",
                "Amount" => $data['amount'] * 100,
                "Provider" => "Bradesco"
            ]
        ]);

        return $response['Payment']['Url'] ?? "Erro ao gerar boleto com a Cielo.";
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws Exception
     */
    public function payWithCreditCard(array $data)
    {
        $url = "{$this->apiUrl}/1/sales/";
        $headers = [
            "MerchantId: {$this->merchantId}",
            "MerchantKey: {$this->merchantKey}",
            "Content-Type: application/json"
        ];

        $response = HttpClient::post($url, $headers, [
            "MerchantOrderId" => uniqid(),
            "Customer" => ["Name" => $data['customer']['name']],
            "Payment" => [
                "Type" => "CreditCard",
                "Amount" => $data['amount'] * 100,
                "CreditCard" => [
                    "CardNumber" => $data['card']['number'],
                    "Holder" => $data['card']['holder'],
                    "ExpirationDate" => $data['card']['expiry'],
                    "SecurityCode" => $data['card']['cvv'],
                    "Brand" => $data['card']['brand']
                ]
            ]
        ]);

        return $response['Payment']['PaymentId'] ?? "Erro ao processar pagamento com a Cielo.";
    }

    /**
     * @param string $transactionId
     * @return TransactionDetails
     * @throws Exception
     */
    public function getTransactionDetails(string $transactionId)
    {
        $url = "{$this->apiUrl}/1/sales/{$transactionId}";
        $headers = ["MerchantId: {$this->merchantId}", "MerchantKey: {$this->merchantKey}"];

        $response = HttpClient::get($url, $headers);

        return new TransactionDetails(
            $transactionId,
            $response['Payment']['Status'],
            $response['Payment']['Amount'] / 100,
            "credit_card",
            date("Y-m-d H:i:s")
        );
    }
}
