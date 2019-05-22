<?php

namespace Braspag\API\Request;

use Braspag\AccessToken;
use Braspag\API\Environment;
use Braspag\API\Payment;

class UpdateSaleRequest extends AbstractRequest
{
    /** @var Environment $environment */
    private $environment;

    /** @var string $type */
    private $type;

    /** @var integer */
    private $serviceTaxAmount;

    /** @var integer */
    private $amount;

    /**
     * UpdateSaleRequest constructor.
     *
     * @param string $type
     * @param AccessToken $accessToken
     * @param Environment $environment
     */
    public function __construct($type, AccessToken $accessToken, Environment $environment)
    {
        parent::__construct($accessToken);

        $this->environment = $environment;
        $this->type = $type;
    }

    /**
     * @param $paymentId
     * @return Payment
     * @throws BraspagRequestException
     */
    public function execute($paymentId)
    {
        $url = $this->environment->getApiUrl() . '1/sales/' . $paymentId . '/' . $this->type;
        $params = [];

        if ($this->amount != null) {
            $params['amount'] = $this->amount;
        }

        if ($this->serviceTaxAmount != null) {
            $params['serviceTaxAmount'] = $this->serviceTaxAmount;
        }

        $url .= '?' . http_build_query($params);

        return $this->sendRequest('PUT', $url);
    }

    /**
     * @param $json
     *
     * @return Payment
     */
    protected function unserialize($json)
    {
        return Payment::fromJson($json);
    }

    /**
     * @return integer
     */
    public function getServiceTaxAmount()
    {
        return $this->serviceTaxAmount;
    }

    /**
     * @param $serviceTaxAmount
     *
     * @return $this
     */
    public function setServiceTaxAmount($serviceTaxAmount)
    {
        $this->serviceTaxAmount = $serviceTaxAmount;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }
}