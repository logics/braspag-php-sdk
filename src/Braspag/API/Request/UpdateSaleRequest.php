<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-06
 * Time: 17:31
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braspag\API\Request;

use Braspag\API\Environment;
use Braspag\API\Payment;
use Braspag\API\SplitPayment;
use Braspag\Authenticator;

class UpdateSaleRequest extends AbstractRequest
{
    /** @var string $type */
    private $type;

    /** @var integer */
    private $serviceTaxAmount;

    /** @var integer */
    private $amount;

    /** @var SplitPayment[] */
    private $paymentSplitRules;

    /** @var SplitPayment[] */
    private $voidSplitPayments;

    /**
     * UpdateSaleRequest constructor.
     *
     * @param string $type
     * @param Authenticator $authenticator
     * @param Environment $environment
     * @param bool $isSplitCase Informa se as requisições serão ref. a pagamento com split (MarketPlace)
     */
    public function __construct($type, Authenticator $authenticator, Environment $environment, $isSplitCase = false)
    {
        parent::__construct($authenticator, $environment, $isSplitCase);

        $this->type = $type;
    }

    /**
     * @param $paymentId
     * @return Payment
     * @throws BraspagRequestException
     */
    public function execute($paymentId)
    {
        $url = $this->environment->getCieloApiUrl() . '1/sales/' . $paymentId . '/' . $this->type;
        $params = [];
        $payment = null;

        if ($this->amount != null) {
            $params['amount'] = $this->amount;
        }

        if ($this->serviceTaxAmount != null) {
            $params['serviceTaxAmount'] = $this->serviceTaxAmount;
        }

        if ($this->paymentSplitRules != null && $this->isSplitCase) {
            $payment = new Payment();
            $payment->setSplitPayments($this->paymentSplitRules);
        }

        if ($this->voidSplitPayments != null && $this->isSplitCase) {
            $payment = new Payment();
            $payment->setVoidSplitPayments($this->voidSplitPayments);
        }

        $url .= '?' . http_build_query($params);

        return $this->sendRequest('PUT', $url, $payment);
    }

    /**
     * @param $json
     *
     * @return Payment
     */
    protected function unserialize($json)
    {
        return Payment::fromJson($json);
    }

    /**
     * @return integer
     */
    public function getServiceTaxAmount()
    {
        return $this->serviceTaxAmount;
    }

    /**
     * @param $serviceTaxAmount
     *
     * @return $this
     */
    public function setServiceTaxAmount($serviceTaxAmount)
    {
        $this->serviceTaxAmount = $serviceTaxAmount;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return SplitPayment[]
     */
    public function getPaymentSplitRules(): ?array
    {
        return $this->paymentSplitRules;
    }

    /**
     * @param SplitPayment[] $paymentSplitRules
     * @return self
     */
    public function setPaymentSplitRules(?array $paymentSplitRules): self
    {
        $this->paymentSplitRules = $paymentSplitRules;

        return $this;
    }

    /**
     * @return SplitPayment[]
     */
    public function getVoidSplitPayments(): ?array
    {
        return $this->voidSplitPayments;
    }

    /**
     * @param SplitPayment[] $voidSplitPayments
     * @return self
     */
    public function setVoidSplitPayments(?array $voidSplitPayments): self
    {
        $this->voidSplitPayments = $voidSplitPayments;
        return $this;
    }
}
