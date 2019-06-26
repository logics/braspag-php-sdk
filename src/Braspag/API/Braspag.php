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
    private static $instance;

    /**
     * @var Authenticator
     */
    private $authenticator;

    /**
     * @var Environment
     */
    private $environment;

    /** @var bool */
    private $isSplitCase;

    /**
     * Create an instance of Braspag choosing the environment where the
     * requests will be send
     *
     * @param Authenticator $authenticator The authenticator with
     * @param Environment environment
     *            The environment: {@link Environment::production()} or
     *            {@link Environment::sandbox()}
     * @param bool $isSplitCase
     */
    private function __construct(Authenticator $authenticator, Environment $environment = null, $isSplitCase = false)
    {
        if ($environment == null) {
            $environment = Environment::production();
        }

        $this->authenticator = $authenticator;
        $this->environment = $environment;
        $this->isSplitCase = $isSplitCase;
    }

    /**
     * @param Authenticator $authenticator
     * @param Environment|null $environment
     * @param bool $isSplitCase
     * @return Braspag
     */
    public static function shared(Authenticator $authenticator, Environment $environment = null, $isSplitCase = false)
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($authenticator, $environment, $isSplitCase);
        }

        return self::$instance;
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
        $createSaleRequest = new CreateSaleRequest($this->authenticator, $this->environment, $this->isSplitCase);

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
        $querySaleRequest = new QuerySaleRequest($this->authenticator, $this->environment, $this->isSplitCase);

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
            $this->authenticator,
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
     * @param SplitPayment[] $voidSplitPayments
     *            Split rule for partial cancellation
     *
     * @return Payment The canceled returned by Braspag.
     *
     * @throws \Braspag\API\Request\BraspagRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function cancelSale($paymentId, $amount = null, $voidSplitPayments = null)
    {
        $updateSaleRequest = new UpdateSaleRequest(
            'void',
            $this->authenticator,
            $this->environment,
            $this->isSplitCase
        );
        $updateSaleRequest->setAmount($amount);
        $updateSaleRequest->setVoidSplitPayments($voidSplitPayments);
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
     * @param SplitPayment[] $paymentSplitRules
     *            Split rule for capture, in split cases
     * @param integer $serviceTaxAmount
     *            Amount of the authorization should be destined for the service
     *            charge
     *
     * @return \Braspag\API\Payment The captured Sale.
     *
     *
     * @throws \Braspag\API\Request\BraspagRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function captureSale($paymentId, $amount = null, array $paymentSplitRules = null, $serviceTaxAmount = null)
    {
        $updateSaleRequest = new UpdateSaleRequest(
            'capture',
            $this->authenticator,
            $this->environment,
            $this->isSplitCase
        );

        $updateSaleRequest->setAmount($amount);
        $updateSaleRequest->setServiceTaxAmount($serviceTaxAmount);
        $updateSaleRequest->setPaymentSplitRules($paymentSplitRules);

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
        $tokenizeCardRequest = new TokenizeCardRequest($this->authenticator, $this->environment);

        return $tokenizeCardRequest->execute($card);
    }
}
