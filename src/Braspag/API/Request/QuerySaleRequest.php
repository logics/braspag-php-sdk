<?php

namespace Braspag\API\Request;

use Braspag\API\Environment;
use Braspag\API\Sale;
use Braspag\Authenticator;

class QuerySaleRequest extends AbstractRequest
{
    /**
     * @var Authenticator
     */
    private $authenticator;

    /** @var Environment $environment */
    private $environment;

    /**
     * QuerySaleRequest constructor.
     *
     * @param Authenticator $authenticator
     * @param Environment $environment
     */
    public function __construct(Authenticator $authenticator, Environment $environment)
    {
        parent::__construct();

        $this->authenticator = $authenticator;
        $this->environment = $environment;
    }

    /**
     * @param $paymentId
     * @return Sale
     * @throws BraspagRequestException
     */
    public function execute($paymentId)
    {
        $url = $this->environment->getCieloApiQueryURL() . '1/sales/' . $paymentId;

        return $this->sendRequest('GET', $url);
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
}
