<?php

namespace Braspag\API\Request;

use Braspag\AccessToken;
use Braspag\API\Environment;
use Braspag\API\Sale;

class QuerySaleRequest extends AbstractRequest
{
    /** @var Environment $environment */
    private $environment;

    /**
     * QuerySaleRequest constructor.
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
     * @param $paymentId
     * @return Sale
     * @throws BraspagRequestException
     */
    public function execute($paymentId)
    {
        $url = $this->environment->getApiQueryURL() . '1/sales/' . $paymentId;

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