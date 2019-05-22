<?php

namespace Braspag\API\Request;

use Braspag\AccessToken;
use Braspag\API\CreditCard;
use Braspag\API\Environment;

class TokenizeCardRequest extends AbstractRequest
{
    /** @var Environment $environment */
    private $environment;

    /** @var AccessToken $accessToken */
    private $accessToken;

    /**
     * @param AccessToken $accessToken
     * @param Environment $environment
     */
    public function __construct(AccessToken $accessToken, Environment $environment)
    {
        parent::__construct($accessToken);

        $this->accessToken = $accessToken;
        $this->environment = $environment;
    }

    /**
     * @param $param
     * @return CreditCard
     * @throws BraspagRequestException
     */
    public function execute($param)
    {
        $url = $this->environment->getApiUrl() . '1/card/';

        return $this->sendRequest('POST', $url, $param);
    }

    /**
     * @param $json
     * @return CreditCard
     */
    protected function unserialize($json)
    {
        return CreditCard::fromJson($json);
    }
}