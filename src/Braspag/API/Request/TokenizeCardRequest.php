<?php

namespace Braspag\API\Request;

use Braspag\API\CreditCard;
use Braspag\API\Environment;
use Braspag\Authenticator;

class TokenizeCardRequest extends AbstractRequest
{
    /** @var Environment $environment */
    private $environment;

    /** @var Authenticator $merchant */
    private $authenticator;

    /**
     * @param Authenticator $authenticator
     * @param Environment $environment
     */
    public function __construct(Authenticator $authenticator, Environment $environment)
    {
        parent::__construct([
            'MerchantId: ' . $authenticator->getMerchant()->getId(),
            'MerchantKey: ' . $authenticator->getMerchant()->getKey(),
        ]);

        $this->authenticator = $authenticator;
        $this->environment = $environment;
    }

    /**
     * @param $param
     * @return CreditCard
     * @throws BraspagRequestException
     */
    public function execute($param)
    {
        $url = $this->environment->getCieloApiUrl() . '1/card/';

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
