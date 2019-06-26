<?php

namespace Braspag\API;

class Payment implements BraspagSerializable
{
    const PAYMENTTYPE_CREDITCARD = 'CreditCard';
    const PAYMENTTYPE_SPLITTED_CARDCARD = 'SplittedCreditCard';
    const PAYMENTTYPE_DEBITCARD = 'DebitCard';
    const PAYMENTTYPE_ELECTRONIC_TRANSFER = 'ElectronicTransfer';
    const PAYMENTTYPE_BOLETO = 'Boleto';
    const PROVIDER_BRADESCO = 'Bradesco';
    const PROVIDER_BANCO_DO_BRASIL = 'BancoDoBrasil';
    const PROVIDER_SIMULADO = 'Simulado';

    const STATUS_NOT_FINISHED = 0;      // Aguardando atualização de status
    const STATUS_AUTHORIZED = 1;        // Pagamento apto a ser capturado ou definido como pago
    const STATUS_PAYMENT_CONFIRMED = 2; // Pagamento confirmado e finalizado
    const STATUS_DENIED = 3;            // Pagamento negado por Autorizador
    const STATUS_VOIDED = 10;           // Pagamento cancelado
    const STATUS_REFUNDED = 11;         // Pagamento cancelado após 23:59 do dia de autorização
    const STATUS_PENDING = 12;          // Aguardando Status de instituição financeira
    const STATUS_ABORTED = 13;          // Pagamento cancelado por falha no processamento ou por ação do AF
    const STATUS_SCHEDULED = 20;        // Recorrência agendada

    private $serviceTaxAmount;
    private $installments;

    private $interest;
    private $capture = false;
    private $authenticate = false;
    private $authenticationUrl;
    private $tid;
    private $proofOfSale;
    private $authorizationCode;
    private $softDescriptor;
    private $returnUrl;
    private $provider;
    private $paymentId;
    private $type;
    private $amount;
    private $receivedDate;
    private $capturedAmount;
    private $capturedDate;
    private $voidedAmount;
    private $voidedDate;
    private $currency;
    private $country;
    private $returnCode;
    private $returnMessage;
    private $status;
    private $links;
    private $extraDataCollection;
    private $expirationDate;
    private $url;
    private $number;
    private $boletoNumber;
    private $barCodeNumber;
    private $digitableLine;
    private $recurrent;
    private $recurrentPayment;
    private $creditCard;
    private $debitCard;
    private $address;
    private $assignor;
    private $demonstrative;
    private $identification;
    private $instructions;
    private $reasonCode;
    private $reasonMessage;
    private $providerReturnCode;
    private $providerReturnMessage;

    /** @var bool */
    private $isSplitted;

    /** @var SplitPayment[] */
    private $splitPayments;

    /** @var SplitPayment[] */
    private $voidSplitPayments;

    /** @var FraudAnalysis */
    private $fraudAnalysis;

    /**
     * Payment constructor.
     *
     * @param int $amount
     * @param int $installments
     * @param null|SplitPayment[] $splitPayments
     */
    public function __construct($amount = 0, $installments = null, $splitPayments = null)
    {
        $this->setAmount($amount);
        $this->setInstallments($installments);
        $this->setSplitPayments($splitPayments);
    }

    /**
     * @param $json
     *
     * @return Payment
     */
    public static function fromJson($json)
    {
        $payment = new Payment();
        $payment->populate(json_decode($json));
        return $payment;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->serviceTaxAmount = isset($data->ServiceTaxAmount) ? $data->ServiceTaxAmount : null;
        $this->installments = isset($data->Installments) ? $data->Installments : null;
        $this->interest = isset($data->Interest) ? $data->Interest : null;
        $this->capture = isset($data->Capture) ? !!$data->Capture : false;
        $this->authenticate = isset($data->Authenticate) ? !!$data->Authenticate : false;
        $this->recurrent = isset($data->Recurrent) ? !!$data->Recurrent : false;

        if (isset($data->RecurrentPayment)) {
            $this->recurrentPayment = new RecurrentPayment(false);
            $this->recurrentPayment->populate($data->RecurrentPayment);
        }
        if (isset($data->CreditCard)) {
            $this->creditCard = new CreditCard();
            $this->creditCard->populate($data->CreditCard);
        }
        if (isset($data->DebitCard)) {
            $this->debitCard = new CreditCard();
            $this->debitCard->populate($data->DebitCard);
        }
        if (isset($data->SplitPayments) && is_array($data->SplitPayments)) {
            $this->splitPayments = [];

            foreach ($data->SplitPayments as $splitPaymentData) {
                $splitPay = new SplitPayment();
                $splitPay->populate($splitPaymentData);

                $this->splitPayments[] = $splitPay;
            }
        }
        if (isset($data->VoidSplitPayments) && is_array($data->VoidSplitPayments)) {
            $this->voidSplitPayments = [];

            foreach ($data->VoidSplitPayments as $splitPaymentData) {
                $splitPayment = new SplitPayment();
                $splitPayment->populate($splitPaymentData);

                $this->voidSplitPayments[] = $splitPayment;
            }
        }
        if (isset($data->FraudAnalysis)) {
            $this->fraudAnalysis = (new FraudAnalysis())->populate($data->FraudAnalysis);
        }

        $this->expirationDate = isset($data->ExpirationDate) ? $data->ExpirationDate : null;
        $this->url = isset($data->Url) ? $data->Url : null;
        $this->boletoNumber = isset($data->BoletoNumber) ? $data->BoletoNumber : null;
        $this->barCodeNumber = isset($data->BarCodeNumber) ? $data->BarCodeNumber : null;
        $this->digitableLine = isset($data->DigitableLine) ? $data->DigitableLine : null;
        $this->address = isset($data->Address) ? $data->Address : null;
        $this->authenticationUrl = isset($data->AuthenticationUrl) ? $data->AuthenticationUrl : null;
        $this->tid = isset($data->Tid) ? $data->Tid : null;
        $this->proofOfSale = isset($data->ProofOfSale) ? $data->ProofOfSale : null;
        $this->authorizationCode = isset($data->AuthorizationCode) ? $data->AuthorizationCode : null;
        $this->softDescriptor = isset($data->SoftDescriptor) ? $data->SoftDescriptor : null;
        $this->provider = isset($data->Provider) ? $data->Provider : null;
        $this->paymentId = isset($data->PaymentId) ? $data->PaymentId : null;
        $this->type = isset($data->Type) ? $data->Type : null;
        $this->amount = isset($data->Amount) ? $data->Amount : null;
        $this->receivedDate = isset($data->ReceivedDate) ? $data->ReceivedDate : null;
        $this->capturedAmount = isset($data->CapturedAmount) ? $data->CapturedAmount : null;
        $this->capturedDate = isset($data->CapturedDate) ? $data->CapturedDate : null;
        $this->voidedAmount = isset($data->VoidedAmount) ? $data->VoidedAmount : null;
        $this->voidedDate = isset($data->VoidedDate) ? $data->VoidedDate : null;
        $this->currency = isset($data->Currency) ? $data->Currency : null;
        $this->country = isset($data->Country) ? $data->Country : null;
        $this->returnCode = isset($data->ReturnCode) ? $data->ReturnCode : null;
        $this->returnMessage = isset($data->ReturnMessage) ? $data->ReturnMessage : null;
        $this->status = isset($data->Status) ? $data->Status : null;
        $this->links = isset($data->Links) ? $data->Links : [];
        $this->assignor = isset($data->Assignor) ? $data->Assignor : null;
        $this->demonstrative = isset($data->Demonstrative) ? $data->Demonstrative : null;
        $this->identification = isset($data->Identification) ? $data->Identification : null;
        $this->instructions = isset($data->Instructions) ? $data->Instructions : null;
        $this->reasonCode = isset($data->ReasonCode) ? $data->ReasonCode : null;
        $this->reasonMessage = isset($data->ReasonMessage) ? $data->ReasonMessage : null;
        $this->providerReturnCode = isset($data->ProviderReturnCode) ? $data->ProviderReturnCode : null;
        $this->providerReturnMessage = isset($data->ProviderReturnMessage) ? $data->ProviderReturnMessage : null;
        $this->isSplitted = isset($data->IsSplitted) ? $data->IsSplitted : null;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * @param $securityCode
     * @param $brand
     *
     * @return CreditCard
     */
    public function creditCard($securityCode, $brand)
    {
        $card = $this->newCard($securityCode, $brand);

        if (is_null($this->type)) {
            $this->setType(self::PAYMENTTYPE_CREDITCARD);
        }

        $this->setCreditCard($card);
        return $card;
    }

    /**
     * @param $securityCode
     * @param $brand
     *
     * @return CreditCard
     */
    private function newCard($securityCode, $brand)
    {
        $card = new CreditCard();
        $card->setSecurityCode($securityCode);
        $card->setBrand($brand);
        return $card;
    }

    /**
     * @param $securityCode
     * @param $brand
     *
     * @return CreditCard
     */
    public function debitCard($securityCode, $brand)
    {
        $card = $this->newCard($securityCode, $brand);

        if (is_null($this->type)) {
            $this->setType(self::PAYMENTTYPE_DEBITCARD);
        }

        $this->setDebitCard($card);
        return $card;
    }

    /**
     * @param bool $authorizeNow
     *
     * @return RecurrentPayment
     */
    public function recurrentPayment($authorizeNow = true)
    {
        $recurrentPayment = new RecurrentPayment($authorizeNow);
        $this->setRecurrentPayment($recurrentPayment);
        return $recurrentPayment;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getInstallments()
    {
        return $this->installments;
    }

    /**
     * @param $installments
     *
     * @return $this
     */
    public function setInstallments($installments)
    {
        $this->installments = $installments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInterest()
    {
        return $this->interest;
    }

    /**
     * @param $interest
     *
     * @return $this
     */
    public function setInterest($interest)
    {
        $this->interest = $interest;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCapture()
    {
        return $this->capture;
    }

    /**
     * @param bool $capture
     *
     * @return $this
     */
    public function setCapture($capture)
    {
        $this->capture = $capture;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAuthenticate()
    {
        return $this->authenticate;
    }

    /**
     * @param $authenticate
     *
     * @return $this
     */
    public function setAuthenticate($authenticate)
    {
        $this->authenticate = $authenticate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecurrent()
    {
        return $this->recurrent;
    }

    /**
     * @param $recurrent
     *
     * @return $this
     */
    public function setRecurrent($recurrent)
    {
        $this->recurrent = $recurrent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecurrentPayment()
    {
        return $this->recurrentPayment;
    }

    /**
     * @param $recurrentPayment
     *
     * @return $this
     */
    public function setRecurrentPayment($recurrentPayment)
    {
        $this->recurrentPayment = $recurrentPayment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreditCard()
    {
        return $this->creditCard;
    }

    /**
     * @param CreditCard $creditCard
     *
     * @return $this
     */
    public function setCreditCard(CreditCard $creditCard)
    {
        $this->creditCard = $creditCard;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDebitCard()
    {
        return $this->debitCard;
    }

    /**
     * @param mixed $debitCard
     *
     * @return $this
     */
    public function setDebitCard($debitCard)
    {
        $this->debitCard = $debitCard;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthenticationUrl()
    {
        return $this->authenticationUrl;
    }

    /**
     * @param $authenticationUrl
     *
     * @return $this
     */
    public function setAuthenticationUrl($authenticationUrl)
    {
        $this->authenticationUrl = $authenticationUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * @param $tid
     *
     * @return $this
     */
    public function setTid($tid)
    {
        $this->tid = $tid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProofOfSale()
    {
        return $this->proofOfSale;
    }

    /**
     * @param $proofOfSale
     *
     * @return $this
     */
    public function setProofOfSale($proofOfSale)
    {
        $this->proofOfSale = $proofOfSale;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param $authorizationCode
     *
     * @return $this
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getSoftDescriptor()
    {
        return $this->softDescriptor;
    }

    /**
     * @param $softDescriptor
     *
     * @return $this
     */
    public function setSoftDescriptor($softDescriptor)
    {
        $this->softDescriptor = $softDescriptor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param $returnUrl
     *
     * @return $this
     */
    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param $provider
     *
     * @return $this
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @param $paymentId
     *
     * @return $this
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getReceivedDate()
    {
        return $this->receivedDate;
    }

    /**
     * @param $receivedDate
     *
     * @return $this
     */
    public function setReceivedDate($receivedDate)
    {
        $this->receivedDate = $receivedDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCapturedAmount()
    {
        return $this->capturedAmount;
    }

    /**
     * @param $capturedAmount
     *
     * @return $this
     */
    public function setCapturedAmount($capturedAmount)
    {
        $this->capturedAmount = $capturedAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCapturedDate()
    {
        return $this->capturedDate;
    }

    /**
     * @param $capturedDate
     *
     * @return $this
     */
    public function setCapturedDate($capturedDate)
    {
        $this->capturedDate = $capturedDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoidedAmount()
    {
        return $this->voidedAmount;
    }

    /**
     * @param $voidedAmount
     *
     * @return $this
     */
    public function setVoidedAmount($voidedAmount)
    {
        $this->voidedAmount = $voidedAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoidedDate()
    {
        return $this->voidedDate;
    }

    /**
     * @param $voidedDate
     *
     * @return $this
     */
    public function setVoidedDate($voidedDate)
    {
        $this->voidedDate = $voidedDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param $currency
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturnCode()
    {
        return $this->returnCode;
    }

    /**
     * @param $returnCode
     *
     * @return $this
     */
    public function setReturnCode($returnCode)
    {
        $this->returnCode = $returnCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReturnMessage()
    {
        return $this->returnMessage;
    }

    /**
     * @param $returnMessage
     *
     * @return $this
     */
    public function setReturnMessage($returnMessage)
    {
        $this->returnMessage = $returnMessage;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param $links
     *
     * @return $this
     */
    public function setLinks($links)
    {
        $this->links = $links;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtraDataCollection()
    {
        return $this->extraDataCollection;
    }

    /**
     * @param $extraDataCollection
     *
     * @return $this
     */
    public function setExtraDataCollection($extraDataCollection)
    {
        $this->extraDataCollection = $extraDataCollection;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param $expirationDate
     *
     * @return $this
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param $number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBoletoNumber()
    {
        return $this->boletoNumber;
    }

    /**
     * @param $boletoNumber
     *
     * @return $this
     */
    public function setBoletoNumber($boletoNumber)
    {
        $this->boletoNumber = $boletoNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBarCodeNumber()
    {
        return $this->barCodeNumber;
    }

    /**
     * @param $barCodeNumber
     *
     * @return $this
     */
    public function setBarCodeNumber($barCodeNumber)
    {
        $this->barCodeNumber = $barCodeNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigitableLine()
    {
        return $this->digitableLine;
    }

    /**
     * @param $digitableLine
     *
     * @return $this
     */
    public function setDigitableLine($digitableLine)
    {
        $this->digitableLine = $digitableLine;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAssignor()
    {
        return $this->assignor;
    }

    /**
     * @param $assignor
     *
     * @return $this
     */
    public function setAssignor($assignor)
    {
        $this->assignor = $assignor;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDemonstrative()
    {
        return $this->demonstrative;
    }

    /**
     * @param $demonstrative
     *
     * @return $this
     */
    public function setDemonstrative($demonstrative)
    {
        $this->demonstrative = $demonstrative;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * @param $identification
     *
     * @return $this
     */
    public function setIdentification($identification)
    {
        $this->identification = $identification;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @param $instructions
     *
     * @return $this
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;
        return $this;
    }

    /**
     * @return SplitPayment[]
     */
    public function getSplitPayments(): ?array
    {
        return $this->splitPayments;
    }

    /**
     * @param SplitPayment[] $splitPayments
     * @return Payment
     */
    public function setSplitPayments(?array $splitPayments): self
    {
        $this->splitPayments = $splitPayments;
        return $this;
    }

    /**
     * @return FraudAnalysis
     */
    public function getFraudAnalysis(): FraudAnalysis
    {
        return $this->fraudAnalysis;
    }

    /**
     * @param FraudAnalysis $fraudAnalysis
     * @return Payment
     */
    public function setFraudAnalysis(FraudAnalysis $fraudAnalysis): self
    {
        $this->fraudAnalysis = $fraudAnalysis;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @param mixed $reasonCode
     * @return Payment
     */
    public function setReasonCode($reasonCode): self
    {
        $this->reasonCode = $reasonCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReasonMessage()
    {
        return $this->reasonMessage;
    }

    /**
     * @param mixed $reasonMessage
     * @return Payment
     */
    public function setReasonMessage($reasonMessage): self
    {
        $this->reasonMessage = $reasonMessage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProviderReturnCode()
    {
        return $this->providerReturnCode;
    }

    /**
     * @param mixed $providerReturnCode
     * @return Payment
     */
    public function setProviderReturnCode($providerReturnCode): self
    {
        $this->providerReturnCode = $providerReturnCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProviderReturnMessage()
    {
        return $this->providerReturnMessage;
    }

    /**
     * @param mixed $providerReturnMessage
     * @return Payment
     */
    public function setProviderReturnMessage($providerReturnMessage): self
    {
        $this->providerReturnMessage = $providerReturnMessage;
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
     * @return Payment
     */
    public function setVoidSplitPayments(?array $voidSplitPayments): self
    {
        $this->voidSplitPayments = $voidSplitPayments;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSplitted(): bool
    {
        return $this->isSplitted;
    }

    /**
     * @param bool $isSplitted
     * @return Payment
     */
    public function setIsSplitted(bool $isSplitted): self
    {
        $this->isSplitted = $isSplitted;
        return $this;
    }
}
