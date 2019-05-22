<?php

namespace Braspag\API\Request;

use Braspag\AccessToken;
use Braspag\API\Environment;
use Braspag\API\Sale;

class CreateSaleRequest extends AbstractRequest
{
    /** @var Environment $environment */
    private $environment;

    /**
     * CreateSaleRequest constructor.
     *
     * @param AccessToken    $accessToken
     * @param Environment $environment
     */
    public function __construct(AccessToken $accessToken, Environment $environment)
    {
        parent::__construct($accessToken);

        $this->environment = $environment;
    }

    /**
     * @param $sale
     *
     * @return Sale
     * @throws BraspagRequestException
     */
    public function execute($sale)
    {
        $url = $this->environment->getApiUrl() . '1/sales/';

        return $this->sendRequest('POST', $url, $sale);
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
