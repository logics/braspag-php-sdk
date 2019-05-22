<?php

namespace Braspag;

class AccessToken
{
    /**
     * The API Cielo E-Commerce Client ID
     * @var string
     */
    private $clientId;

    /**
     * The Braspag Client Secret
     * @var string
     */
    private $clientSecret;

    /**
     * @param $clientId
     * @param $clientSecret
     */
    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Returns the result of concatenation with clientId and clientSecret encoded MIME base64
     * @return string
     */
    public function getTokenBase64()
    {
        return base64_encode($this->clientId . $this->clientSecret);
    }

    /**
     * Gets the merchant identification number on Cielo
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Gets the client secret identification on Braspag
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

}