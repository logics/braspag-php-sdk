<?php

namespace Braspag\API\Request;

use Braspag\API\CreditCard;

class TokenizeCardRequest extends AbstractRequest
{
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
