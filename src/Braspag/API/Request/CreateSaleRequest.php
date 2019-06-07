<?php

namespace Braspag\API\Request;

use Braspag\API\Sale;

class CreateSaleRequest extends AbstractRequest
{
    /**
     * @param Sale $sale
     *
     * @return Sale
     * @throws BraspagRequestException
     */
    public function execute($sale)
    {
        $apiUrl = $this->environment->getCieloApiUrl();

        $url = $apiUrl . '1/sales/';

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
