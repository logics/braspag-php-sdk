<?php

namespace Braspag\API\Request;

use Braspag\API\RecurrentPayment;

class QueryRecurrentPaymentRequest extends AbstractRequest
{
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
