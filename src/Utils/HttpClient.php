<?php

namespace PaymentGateway\Utils;

use Exception;

class HttpClient
{
    /**
     * @param string $url
     * @param array $headers
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public static function post(string $url, array $headers = [], array $data = [])
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 400) {
            throw new Exception("Erro na requisição: " . $response);
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    /**
     * @param string $url
     * @param array $headers
     * @return mixed
     * @throws Exception
     */
    public static function get(string $url, array $headers = [])
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 400) {
            throw new Exception("Erro na requisição: " . $response);
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
