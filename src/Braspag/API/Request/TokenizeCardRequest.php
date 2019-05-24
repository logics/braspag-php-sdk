<?php

namespace Braspag\API\Request;

use Braspag\API\CreditCard;
use Braspag\API\Environment;
use Braspag\Merchant;

class TokenizeCardRequest extends AbstractRequest
{
    /** @var Environment $environment */
    private $environment;

    /** @var Merchant $merchant */
    private $merchant;

    /**
     * @param Merchant $merchant
     * @param Environment $environment
     */
    public function __construct(Merchant $merchant, Environment $environment)
    {
        parent::__construct($merchant);

        $this->merchant = $merchant;
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
