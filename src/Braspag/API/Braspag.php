<?php

namespace Braspag\API;

use Braspag\API\Request\CreateSaleRequest;
use Braspag\API\Request\QueryRecurrentPaymentRequest;
use Braspag\API\Request\QuerySaleRequest;
use Braspag\API\Request\TokenizeCardRequest;
use Braspag\API\Request\UpdateSaleRequest;
use Braspag\Authenticator;

/**
 * Class Braspag
 * @package Braspag\API
 */
class Braspag
{
    /**
     * @var Authenticator
     */
    private $authenticator;

    /**
     * @var Environment
     */
    private $environment;

    /**
     * Create an instance of Braspag choosing the environment where the
     * requests will be send
     *
     * @param Authenticator $authenticator The authenticator with
     * @param Environment environment
     *            The environment: {@link Environment::production()} or
     *            {@link Environment::sandbox()}
     */
    public function __construct(Authenticator $authenticator, Environment $environment = null)
    {
        if ($environment == null) {
            $environment = Environment::production();
        }

        $this->authenticator = $authenticator;
        $this->environment = $environment;
    }

    /**
     * Send the Sale to be created and return the Sale with tid and the status
     * returned by Braspag.
     *
     * @param Sale $sale The preconfigured Sale
     *
     * @return Sale The Sale with authorization, tid, etc. returned by Braspag.
     *
     * @throws \Braspag\API\Request\BraspagRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function createSale(Sale $sale)
    {
        $createSaleRequest = new CreateSaleRequest($this->authenticator->getAccessToken(), $this->environment);

        return $createSaleRequest->execute($sale);
    }

    /**
     * Query a Sale on Braspag by paymentId
     *
     * @param string $paymentId
     *            The paymentId to be queried
     *
     * @return Sale The Sale with authorization, tid, etc. returned by Braspag.
     *
     * @throws \Braspag\API\Request\BraspagRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function getSale($paymentId)
    {
        $querySaleRequest = new QuerySaleRequest($this->authenticator->getAccessToken(), $this->environment);

        return $querySaleRequest->execute($paymentId);
    }

    /**
     * Query a RecurrentPayment on Braspag by RecurrentPaymentId
     *
     * @param string $recurrentPaymentId
     *            The RecurrentPaymentId to be queried
     *
     * @return \Braspag\API\RecurrentPayment
     *            The RecurrentPayment with authorization, tid, etc. returned by Braspag.
     *
     * @throws \Braspag\API\Request\BraspagRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function getRecurrentPayment($recurrentPaymentId)
    {
        $queryRecurrentPaymentRequest = new QueryRecurrentPaymentRequest(
            $this->authenticator->getMerchant(),
            $this->environment
        );

        return $queryRecurrentPaymentRequest->execute($recurrentPaymentId);
    }

    /**
     * Cancel a Sale on Braspag by paymentId and speficying the amount
     *
     * @param string $paymentId
     *            The paymentId to be queried
     * @param integer $amount
     *            Order value in cents
     *
     * @return Sale The Sale with authorization, tid, etc. returned by Braspag.
     *
     * @throws \Braspag\API\Request\BraspagRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function cancelSale($paymentId, $amount = null)
    {
        $updateSaleRequest = new UpdateSaleRequest('void', $this->authenticator->getAccessToken(), $this->environment);
        $updateSaleRequest->setAmount($amount);

        return $updateSaleRequest->execute($paymentId);
    }

    /**
     * Capture a Sale on Braspag by paymentId and specifying the amount and the
     * serviceTaxAmount
     *
     * @param string $paymentId
     *            The paymentId to be captured
     * @param integer $amount
     *            Amount of the authorization to be captured
     * @param integer $serviceTaxAmount
     *            Amount of the authorization should be destined for the service
     *            charge
     *
     * @return \Braspag\API\Sale The captured Sale.
     *
     *
     * @throws \Braspag\API\Request\BraspagRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function captureSale($paymentId, $amount = null, $serviceTaxAmount = null)
    {
        $updateSaleRequest = new UpdateSaleRequest(
            'capture',
            $this->authenticator->getAccessToken(),
            $this->environment
        );

        $updateSaleRequest->setAmount($amount);
        $updateSaleRequest->setServiceTaxAmount($serviceTaxAmount);

        return $updateSaleRequest->execute($paymentId);
    }

    /**
     * @param CreditCard $card
     *
     * @return CreditCard
     * @throws Request\BraspagRequestException
     */
    public function tokenizeCard(CreditCard $card)
    {
        $tokenizeCardRequest = new TokenizeCardRequest($this->authenticator->getMerchant(), $this->environment);

        return $tokenizeCardRequest->execute($card);
    }
}
