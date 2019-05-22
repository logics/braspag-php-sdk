<?php

namespace Braspag\API\Request;

use Braspag\AccessToken;
use Braspag\API\Sale;

abstract class AbstractRequest
{
    private $accessToken;

    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public abstract function execute($param);

    protected abstract function unserialize($json);

    /**
     * @param $method
     * @param $url
     * @param Sale|null $sale
     * @return null
     * @throws BraspagRequestException
     */
    protected function sendRequest($method, $url, Sale $sale = null)
    {
        $headers = [
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'User-Agent: Braspag/1.0 PHP SDK',
            'Authorization: Bearer ' . $this->accessToken->getTokenBase64(),
            'MerchantId: ' . $this->accessToken->getMerchant()->getId(),
            'MerchantKey: ' . $this->accessToken->getMerchant()->getKey(),
            'RequestId: ' . uniqid()
        ];

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        if ($sale !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($sale));

            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Length: 0';
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            throw new \RuntimeException('Curl error: ' . curl_error($curl));
        }

        curl_close($curl);

        return $this->readResponse($statusCode, $response);
    }

    /**
     * @param $statusCode
     * @param $responseBody
     * @return mixed
     * @throws BraspagRequestException
     */
    protected function readResponse($statusCode, $responseBody)
    {
        $unserialized = null;

        switch ($statusCode) {
            case 200:
            case 201:
                $unserialized = $this->unserialize($responseBody);
                break;
            case 400:
                $exception = null;
                $response = json_decode($responseBody);

                foreach ($response as $error) {
                    $braspagError = new BraspagError($error->Message, $error->Code);
                    $exception = new BraspagRequestException('Request Error', $statusCode, $exception);
                    $exception->setBraspagError($braspagError);
                }

                throw $exception;
            case 404:
                throw new BraspagRequestException('Resource not found', 404, null);
            default:
                throw new BraspagRequestException('Unknown status', $statusCode);
        }

        return $unserialized;
    }
}