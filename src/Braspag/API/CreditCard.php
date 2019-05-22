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
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param $cardNumber
     *
     * @return $this
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * @param $holder
     *
     * @return $this
     */
    public function setHolder($holder)
    {
        $this->holder = $holder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param $expirationDate
     *
     * @return $this
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * @param $securityCode
     *
     * @return $this
     */
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSaveCard()
    {
        return $this->saveCard;
    }

    /**
     * @param $saveCard
     *
     * @return $this
     */
    public function setSaveCard($saveCard)
    {
        $this->saveCard = $saveCard;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param $brand
     *
     * @return $this
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardToken()
    {
        return $this->cardToken;
    }

    /**
     * @param $cardToken
     *
     * @return $this
     */
    public function setCardToken($cardToken)
    {
        $this->cardToken = $cardToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @param string $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    /**
     * @return \stdClass
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param \stdClass $links
     */
    public function setLinks($links)
    {
        $this->links = $links;
    }
}