<?php

namespace Braspag;

use Braspag\API\Request\BraspagError;
use Braspag\API\Request\BraspagRequestException;

/**
 * Class Authenticator
 * @package Braspag
 */
class Authenticator
{
    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string
     */
    private $merchantKey;

    /**
     * @var AccessToken
     */
    private $accessToken;

    /**
     * @var Merchant
     */
    private $merchant;

    /**
     * Authenticator constructor.
     *
     * @param string $clientSecret The ClientSecret token on Braspag
     * @param string $merchantId The MerchantId token on Cielo
     * @param string $merchantKey The MerchantKey token on Cielo
     */
    public function __construct(string $clientSecret, string $merchantId, string $merchantKey)
    {
        $this->clientSecret = $clientSecret;
        $this->merchantId = $merchantId;
        $this->merchantKey = $merchantKey;
        $this->merchant = new Merchant($merchantId, $merchantKey);
    }

    /**
     * @param Environment $environment
     * @throws API\Request\BraspagRequestException
     */
    public function authenticate(Environment $environment)
    {
        $this->accessToken = $this->execute($environment);
    }

    public function getAuthenticationHeaders($isSplitCase = false): array
    {
        return $isSplitCase ? [
            'Authorization: Bearer ' . ($this->accessToken != null ? $this->accessToken->getToken() : ''),
        ] : [
            'MerchantId: ' . $this->merchantId,
            'MerchantKey: ' . $this->merchantKey,
        ];
    }

    public function isAuthenticated(): bool
    {
        return $this->accessToken != null && !is_null($this->accessToken->getToken());
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }

    /**
     * @return Merchant
     */
    public function getMerchant(): Merchant
    {
        return $this->merchant;
    }

    /**
     * @param Environment $environment
     * @return AccessToken
     * @throws API\Request\BraspagRequestException
     */
    public function execute($environment)
    {
        $url = $environment->getApiAuthUrl() . 'oauth2/token';

        $params = 'grant_type=client_credentials';

        return $this->sendAuthRequest($url, $params);
    }

    /**
     * @param string $url
     * @param string $content
     * @return mixed
     * @throws API\Request\BraspagRequestException
     */
    protected function sendAuthRequest(string $url, string $content)
    {
        $curl = curl_init($url);

        $headers = [
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'Accept-Encoding: gzip',
            'User-Agent: Braspag/1.0 PHP SDK',
        ];

        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_POST, true);

        if ($content !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        } else {
            $headers[] = 'Content-Length: 0';
        }

        curl_setopt($curl, CURLOPT_USERPWD, "{$this->merchantId}:{$this->clientSecret}");
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
     * @param  $statusCode
     * @param  $responseBody
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

    /**
     * @param  $json
     * @return mixed
     */
    protected function unserialize($json)
    {
        return AccessToken::fromJson($json);
    }
}
