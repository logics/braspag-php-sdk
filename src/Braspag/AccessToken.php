<?php

namespace Braspag;

class AccessToken implements TokenAuthenticable
{
    /**
     * @var string
     */
    private $merchantId;

    /**
     * The Braspag Client Secret
     * @var string
     */
    private $clientSecret;

    /**
     * @param string $merchantId    The MerchantID key on Cielo
     * @param string $clientSecret  The ClientSecret key on Braspag
     */
    public function __construct(string $merchantId, string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
        $this->merchantId = $merchantId;
    }

    public function getAuthenticationHeaders(): array
    {
        return [
            'Authorization: Bearer ' . $this->getTokenBase64(),
        ];
    }

    /**
     * Returns the result of concatenation with clientId and clientSecret encoded MIME base64
     * @return string
     */
    public function getTokenBase64()
    {
        return base64_encode($this->merchantId . $this->clientSecret);
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
