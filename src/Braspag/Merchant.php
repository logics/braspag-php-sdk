<?php

namespace Braspag;

class Merchant implements TokenAuthenticable
{
    /** @var string */
    private $id;

    /** @var string */
    private $key;

    /**
     * Merchant constructor.
     *
     * @param $id
     * @param $key
     */
    public function __construct(string $id, string $key)
    {
        $this->id = $id;
        $this->key = $key;
    }

    public function getAuthenticationHeaders(): array
    {
        return [
            'MerchantId: ' . $this->getId(),
            'MerchantKey: ' . $this->getKey(),
        ];
    }

    /**
     * Gets the merchant identification number
     *
     * @return string the merchant identification number on Cielo
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the merchant identification key
     *
     * @return string the merchant identification key on Cielo
     */
    public function getKey()
    {
        return $this->key;
    }
}
