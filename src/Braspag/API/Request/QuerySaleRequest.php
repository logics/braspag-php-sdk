<?php

namespace Braspag\API\Request;

use Braspag\API\Sale;

class QuerySaleRequest extends AbstractRequest
{
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
