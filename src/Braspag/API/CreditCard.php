<?php

namespace Braspag\API;

class CreditCard implements BraspagSerializable
{
    const VISA = 'Visa';
    const MASTERCARD = 'Master';
    const AMEX = 'Amex';
    const ELO = 'Elo';
    const AURA = 'Aura';
    const JCB = 'JCB';
    const DINERS = 'Diners';
    const DISCOVER = 'Discover';
    const HIPERCARD = 'Hipercard';

    /** @var string $cardNumber */
    private $cardNumber;

    /** @var string $holder */
    private $holder;

    /** @var string $expirationDate */
    private $expirationDate;

    /** @var string $securityCode */
    private $securityCode;

    /** @var bool $saveCard */
    private $saveCard = false;

    /** @var string $brand */
    private $brand;

    /** @var string $cardToken */
    private $cardToken;

    /** @var string $customerName */
    private $customerName;

    /** @var \stdClass $links */
    private $links;

    /**
     * @param string $json
     *
     * @return CreditCard
     */
    public static function fromJson($json)
    {
        $object = \json_decode($json);
        $cardToken = new CreditCard();
        $cardToken->populate($object);
        return $cardToken;
    }

    /**
     * @inheritdoc
     */
    public function populate(\stdClass $data)
    {
        $this->cardNumber = isset($data->CardNumber) ? $data->CardNumber : null;
        $this->holder = isset($data->Holder) ? $data->Holder : null;
        $this->expirationDate = isset($data->ExpirationDate) ? $data->ExpirationDate : null;
        $this->securityCode = isset($data->SecurityCode) ? $data->SecurityCode : null;
        $this->saveCard = isset($data->SaveCard) ? !!$data->SaveCard : false;
        $this->brand = isset($data->Brand) ? $data->Brand : null;
        $this->cardToken = isset($data->CardToken) ? $data->CardToken : null;
        $this->links = isset($data->Links) ? $data->Links : new \stdClass();
        $this->customerName = isset($data->CustomerName) ? $data->CustomerName : null;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * @return string
     */
    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    /**
     * @param string $cardNumber
     * @return CreditCard
     */
    public function setCardNumber(string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getHolder(): string
    {
        return $this->holder;
    }

    /**
     * @param string $holder
     * @return CreditCard
     */
    public function setHolder(string $holder): self
    {
        $this->holder = $holder;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    /**
     * @param string $expirationDate
     * @return CreditCard
     */
    public function setExpirationDate(string $expirationDate): self
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecurityCode(): string
    {
        return $this->securityCode;
    }

    /**
     * @param string $securityCode
     * @return CreditCard
     */
    public function setSecurityCode(string $securityCode): self
    {
        $this->securityCode = $securityCode;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSaveCard(): bool
    {
        return $this->saveCard;
    }

    /**
     * @param bool $saveCard
     * @return CreditCard
     */
    public function setSaveCard(bool $saveCard): self
    {
        $this->saveCard = $saveCard;
        return $this;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     * @return CreditCard
     */
    public function setBrand(string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardToken(): string
    {
        return $this->cardToken;
    }

    /**
     * @param string $cardToken
     * @return CreditCard
     */
    public function setCardToken(string $cardToken): self
    {
        $this->cardToken = $cardToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    /**
     * @param string $customerName
     * @return CreditCard
     */
    public function setCustomerName(string $customerName): self
    {
        $this->customerName = $customerName;
        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getLinks(): \stdClass
    {
        return $this->links;
    }

    /**
     * @param \stdClass $links
     * @return CreditCard
     */
    public function setLinks(\stdClass $links): self
    {
        $this->links = $links;
        return $this;
    }
}
