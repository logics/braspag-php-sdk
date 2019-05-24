<?php

namespace Braspag;

/**
 * Class Authenticator
 * @package Braspag
 */
class Authenticator
{
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
        $this->accessToken = new AccessToken($merchantId, $clientSecret);
        $this->merchant = new Merchant($merchantId, $merchantKey);
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
}
