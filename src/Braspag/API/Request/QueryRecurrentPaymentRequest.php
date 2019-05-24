<?php

namespace Braspag\API\Request;

use Braspag\API\Environment;
use Braspag\API\RecurrentPayment;
use Braspag\Merchant;

class QueryRecurrentPaymentRequest extends AbstractRequest
{
    private $environment;

    /**
     * QueryRecurrentPaymentRequest constructor.
     *
     * @param Merchant $merchant
     * @param Environment $environment
     */
    public function __construct(Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);

        $this->environment = $environment;
    }

    /**
     * @param $recurrentPaymentId
     * @return RecurrentPayment
     * @throws BraspagRequestException
     */
    public function execute($recurrentPaymentId)
    {
        $url = $this->environment->getApiQueryURL() . '1/RecurrentPayment/' . $recurrentPaymentId;

        return $this->sendRequest('GET', $url);
    }

    /**
     * @param $json
     *
     * @return RecurrentPayment
     */
    protected function unserialize($json)
    {
        return RecurrentPayment::fromJson($json);
    }
}
