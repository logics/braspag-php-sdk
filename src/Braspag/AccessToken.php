<?php

namespace Braspag;

class AccessToken
{
    /**
     * @var Merchant
     */
    private $merchant;

    /**
     * The Braspag Client Secret
     * @var string
     */
    private $clientSecret;

    /**
     * @param Merchant $merchant
     * @param string $clientSecret  The clientSecret key on Braspag
     */
    public function __construct(Merchant $merchant, $clientSecret)
    {
        $this->clientSecret = $clientSecret;
        $this->merchant = $merchant;
    }

    /**
     * Returns the result of concatenation with clientId and clientSecret encoded MIME base64
     * @return string
     */
    public function getTokenBase64()
    {
        return base64_encode($this->merchant->getId() . $this->clientSecret);
    }

    /**
     * @return Merchant
     */
    public function getMerchant(): Merchant
    {
        return $this->merchant;
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