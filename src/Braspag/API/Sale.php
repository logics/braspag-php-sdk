<?php

namespace Braspag\API;

class Sale implements BraspagSerializable
{
    private $merchantOrderId;

    /** @var Customer */
    private $customer;

    /** @var Payment */
    private $payment;

    public function __construct($merchantOrderId = null)
    {
        $this->setMerchantOrderId($merchantOrderId);
    }

    public function jsonSerialize()
    {
        $array = array_filter(get_object_vars($this));
        $array = array_combine(
            array_map('ucfirst', array_keys($array)),
            array_values($array)
        );

        return $array;
    }

    public function populate(\stdClass $data)
    {
        $dataProps = get_object_vars($data);

        if (isset($dataProps['Customer'])) {
            $this->customer = new Customer();
            $this->customer->populate($data->Customer);
        }

        if (isset($dataProps['Payment'])) {
            $this->payment = new Payment();
            $this->payment->populate($data->Payment);
        }

        if (isset($dataProps['MerchantOrderId'])) {
            $this->merchantOrderId = $data->MerchantOrderId;
        }
    }

    /**
     * @param $json
     *
     * @return Sale
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $sale = new Sale();
        $sale->populate($object);

        return $sale;
    }

    public function customer($name)
    {
        $customer = new Customer($name);
        $this->setCustomer($customer);

        return $customer;
    }

    /**
     * @param int $amount Valor do Pagamento
     * @param int $installments Número de parcelas
     * @param null|SplitPayment[] $splitPayments Regras de Split
     * @return Payment
     */
    public function payment($amount, $installments = 1, $splitPayments = null)
    {
        $payment = new Payment($amount, $installments, $splitPayments);

        if (!is_null($splitPayments) && count($splitPayments) > 0) {
            $payment->setType(Payment::PAYMENTTYPE_SPLITTED_CARDCARD);
        }

        $this->setPayment($payment);

        return $payment;
    }

    /**
     * @return mixed
     */
    public function getMerchantOrderId()
    {
        return $this->merchantOrderId;
    }

    /**
     * @param mixed $merchantOrderId
     * @return Sale
     */
    public function setMerchantOrderId($merchantOrderId): self
    {
        $this->merchantOrderId = $merchantOrderId;
        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return Sale
     */
    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     * @return Sale
     */
    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;
        return $this;
    }
}
