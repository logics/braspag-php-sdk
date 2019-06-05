<?php

namespace Braspag\API\Request;

use Braspag\API\Environment;
use Braspag\API\RecurrentPayment;
use Braspag\Authenticator;

class QueryRecurrentPaymentRequest extends AbstractRequest
{
    /**
     * @var Authenticator
     */
    private $authenticator;

    private $environment;

    /**
     * QueryRecurrentPaymentRequest constructor.
     *
     * @param Authenticator $authenticator
     * @param Environment $environment
     */
    public function __construct(Authenticator $authenticator, Environment $environment)
    {
        parent::__construct();

        $this->environment = $environment;
        $this->authenticator = $authenticator;
    }

    /**
     * @param $recurrentPaymentId
     * @return RecurrentPayment
     * @throws BraspagRequestException
     */
    public function execute($recurrentPaymentId)
    {
        $url = $this->environment->getCieloApiQueryURL() . '1/RecurrentPayment/' . $recurrentPaymentId;

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
