<?php

namespace Braspag\API\Request;

use Braspag\API\Environment;
use Braspag\Authenticator;

/**
 * Class AbstractRequest
 *
 * @package Braspag\API\Request
 */
abstract class AbstractRequest
{
    /**
     * @var array
     */
    private $authHeaders = [];

    /**
     * @var Authenticator
     */
    protected $authenticator;

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @var bool
     */
    protected $isSplitCase;

    /**
     * AbstractRequest constructor.
     * @param Authenticator $authenticator
     * @param Environment $environment
     * @param bool $isSplitCase Informa se as requisições serão ref. a pagamento com split (MarketPlace)
     */
    public function __construct(
        Authenticator $authenticator,
        Environment $environment,
        $isSplitCase = false
    ) {
        $this->authenticator = $authenticator;
        $this->environment = $environment;
        $this->isSplitCase = $isSplitCase;
    }

    /**
     * @throws BraspagRequestException
     */
    private function authenticateIfNeeds()
    {
        // Se for um caso de split e não estiver autenticado, faz a autenticação guarda os headers necessários
        if ($this->authenticator->isAuthenticated() == false && $this->isSplitCase) {
            $this->authenticator->authenticate($this->environment);
        }

        if (count($this->authHeaders) == 0) {
            $this->authHeaders = $this->authenticator->getAuthenticationHeaders($this->isSplitCase);
        }
    }

    /**
     * @param  $param
     * @return mixed
     */
    abstract public function execute($param);

    /**
     * @param  $method
     * @param  $url
     * @param  \JsonSerializable|null $content
     * @return null
     * @throws BraspagRequestException
     */
    protected function sendRequest($method, $url, \JsonSerializable $content = null)
    {
        $this->authenticateIfNeeds();

        $headers = array_merge([
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'User-Agent: Braspag/1.0 PHP SDK',
        ], $this->authHeaders);

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

        if ($content !== null) {
            $postFields = json_encode($content);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);

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
    abstract protected function unserialize($json);

    /**
     * @param array $authHeaders
     * @return self
     */
    protected function setAuthHeaders(array $authHeaders): self
    {
        $this->authHeaders = $authHeaders;
        return $this;
    }
}
