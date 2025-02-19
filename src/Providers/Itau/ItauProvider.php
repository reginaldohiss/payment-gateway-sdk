<?php

namespace PaymentGateway\Providers\Itau;

use PaymentGateway\Contracts\PaymentProviderInterface;
use PaymentGateway\Utils\HttpClient;
use PaymentGateway\Config\Environment;
use PaymentGateway\TransactionDetails;
use Exception;

class ItauProvider implements PaymentProviderInterface
{
    /**
     * @var string
     */
    private string $apiUrl;
    /**
     * @var string|array|false
     */
    private string $clientId;
    /**
     * @var string|array|false
     */
    private string $clientSecret;
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
        $this->clientId = getenv('ITAU_CLIENT_ID') ?: 'seu-client-id';
        $this->clientSecret = getenv('ITAU_CLIENT_SECRET') ?: 'seu-client-secret';

        $this->apiUrl = $environment->isProduction() ?
            "https://api.itau.com.br" :
            "https://sandbox.api.itau.com.br";
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function authenticate()
    {
        $url = "{$this->apiUrl}/oauth/token";
        $headers = ["Content-Type: application/x-www-form-urlencoded"];

        $data = [
            "grant_type" => "client_credentials",
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret
        ];

        $response = HttpClient::post($url, $headers, $data);
        return $response['access_token'] ?? throw new Exception("Erro ao autenticar no Itaú.");
    }

    /**
     * @param array $data
     * @return mixed|string
     * @throws Exception
     */
    public function payWithPix(array $data)
    {
        $token = $this->authenticate();
        $url = "{$this->apiUrl}/pix/transactions";
        $headers = ["Authorization: Bearer {$token}", "Content-Type: application/json"];

        $response = HttpClient::post($url, $headers, [
            "amount" => $data['amount'],
            "payer" => $data['payer']
        ]);

        return $response['qr_code'] ?? "Erro ao gerar QR Code Pix no Itaú.";
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function payWithBoleto(array $data)
    {
        throw new Exception("O Itaú não oferece pagamento via boleto através da API pública.");
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function payWithCreditCard(array $data)
    {
        throw new Exception("O Itaú não oferece pagamento via cartão diretamente.");
    }

    /**
     * @param string $transactionId
     * @return TransactionDetails
     * @throws Exception
     */
    public function getTransactionDetails(string $transactionId)
    {
        $token = $this->authenticate();
        $url = "{$this->apiUrl}/pix/transactions/{$transactionId}";
        $headers = ["Authorization: Bearer {$token}"];

        $response = HttpClient::get($url, $headers);

        return new TransactionDetails(
            $transactionId,
            $response['status'],
            $response['amount'],
            "pix",
            $response['createdAt']
        );
    }
}
