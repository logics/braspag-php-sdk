<?php

namespace Braspag\API\Request;

use Braspag\AccessToken;
use Braspag\API\Environment;
use Braspag\API\RecurrentPayment;

class QueryRecurrentPaymentRequest extends AbstractRequest
{
    private $environment;

    /**
     * QueryRecurrentPaymentRequest constructor.
     *
     * @param AccessToken $accessToken
     * @param Environment $environment
     */
    public function __construct(AccessToken $accessToken, Environment $environment)
    {
        parent::__construct($accessToken);

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