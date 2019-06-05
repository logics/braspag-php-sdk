<?php

namespace Braspag\API\Request;

use Braspag\API\Environment;
use Braspag\API\Payment;
use Braspag\API\Sale;
use Braspag\Authenticator;

class CreateSaleRequest extends AbstractRequest
{
    /** @var Environment $environment */
    private $environment;

    /**
     * @var Authenticator
     */
    private $auth;

    /**
     * CreateSaleRequest constructor.
     *
     * @param Authenticator    $auth
     * @param Environment $environment
     */
    public function __construct(Authenticator $auth, Environment $environment)
    {
        parent::__construct($auth->getAuthenticationHeaders());

        $this->environment = $environment;
        $this->auth = $auth;
    }

    /**
     * @param Sale $sale
     *
     * @return Sale
     * @throws BraspagRequestException
     */
    public function execute($sale)
    {
        $apiUrl = $sale->getPayment()->getType() == Payment::PAYMENTTYPE_SPLITTED_CARDCARD ?
            $this->environment->getCieloApiUrl() :
            $this->environment->getCieloApiUrl()
        ;

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
