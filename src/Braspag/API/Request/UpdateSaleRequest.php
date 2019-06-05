<?php

namespace Braspag\API\Request;

use Braspag\API\Environment;
use Braspag\API\Sale;
use Braspag\Authenticator;

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
     * @var Authenticator
     */
    private $authenticator;

    /**
     * UpdateSaleRequest constructor.
     *
     * @param string $type
     * @param Authenticator $authenticator
     * @param Environment $environment
     */
    public function __construct($type, Authenticator $authenticator, Environment $environment)
    {
        parent::__construct();

        $this->environment = $environment;
        $this->type = $type;
        $this->authenticator = $authenticator;
    }

    /**
     * @param $paymentId
     * @return Sale
     * @throws BraspagRequestException
     */
    public function execute($paymentId)
    {
        $url = $this->environment->getCieloApiUrl() . '1/sales/' . $paymentId . '/' . $this->type;
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
     * @return Sale
     */
    protected function unserialize($json)
    {
        return Sale::fromJson($json);
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
