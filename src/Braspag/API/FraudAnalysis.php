<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-04
 * Time: 15:04
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braspag\API;

/**
 * Class FraudAnalysis
 * @package Braspag\API
 */
class FraudAnalysis implements BraspagSerializable
{
    const SEQUENCE_ANALYSE_FIRST = 'AnalyseFirst';
    const SEQUENCE_AUTHORIZE_FIRST = 'AuthorizeFirst';

    /**
     * Provedor de análise anti-fraude
     *
     * @var string
     */
    private $provider = 'cybersource';

    /**
     * Tipo de Fluxo para realização da análise de fraude. Primeiro Analise (AnalyseFirst)
     * ou Primeiro Autorização (AuthorizeFirst)
     *
     * @var string
     */
    private $sequence; // = self::SEQUENCE_ANALYSE_FIRST;

    /** @var string */
    private $sequenceCriteria; // = 'OnSuccess';

    /** @var integer */
    private $totalOrderAmount;

    /**
     * @var string
     */
    private $fingerPrintId;

    /** @var Cart */
    private $cart;

    /** @var array */
    private $browser;

    /** @var array */
    private $merchantDefinedFields;

    /**
     * FraudAnalysis constructor.
     * @param string|null $fingerPrint
     * @param int|null $totalOrderAmount
     * @param Cart|null $cart
     * @param array $merchantDefinedFields Campos definidos na tabela MerchantDefinedData (Cybersource) da DOC Braspag
     * @see https://braspag.github.io//manual/antifraude#tabela-31-merchantdefineddata-(cybersource)
     */
    public function __construct(
        string $fingerPrint = null,
        int $totalOrderAmount = null,
        Cart $cart = null,
        array $merchantDefinedFields = []
    ) {
        $this->totalOrderAmount = $totalOrderAmount;
        $this->cart = $cart;
        $this->browser = [
            'CookiesAccepted' => false,
            'BrowserFingerPrint' => $fingerPrint,
            'IpAddress' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',
        ];
        $this->merchantDefinedFields = [];

        foreach ($merchantDefinedFields as $key => $value) {
            $this->merchantDefinedFields[] = [
                'Id' => $key,
                'Value' => $value,
            ];
        }
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->provider = isset($data->Provider) ? $data->Provider : '';
        $this->sequence = isset($data->Sequence) ? $data->Sequence : '';
        $this->sequenceCriteria = isset($data->SequenceCriteria) ? $data->SequenceCriteria : '';
        $this->totalOrderAmount = isset($data->TotalOrderAmount) ? $data->TotalOrderAmount : '';
        $this->fingerPrintId = isset($data->FingerPrintId) ? $data->FingerPrintId : '';

        if (isset($data->Cart)) {
            $cart = (new Cart())->populate($data->Cart);
            $this->cart = $cart;
        }
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param string $provider
     * @return FraudAnalysis
     */
    public function setProvider(string $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * @return string
     */
    public function getSequence(): string
    {
        return $this->sequence;
    }

    /**
     * @param string $sequence
     * @return FraudAnalysis
     */
    public function setSequence(string $sequence): self
    {
        $this->sequence = $sequence;
        return $this;
    }

    /**
     * @return string
     */
    public function getSequenceCriteria(): string
    {
        return $this->sequenceCriteria;
    }

    /**
     * @param string $sequenceCriteria
     * @return FraudAnalysis
     */
    public function setSequenceCriteria(string $sequenceCriteria): self
    {
        $this->sequenceCriteria = $sequenceCriteria;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalOrderAmount(): int
    {
        return $this->totalOrderAmount;
    }

    /**
     * @param int $totalOrderAmount
     * @return FraudAnalysis
     */
    public function setTotalOrderAmount(int $totalOrderAmount): self
    {
        $this->totalOrderAmount = $totalOrderAmount;
        return $this;
    }

    /**
     * @return string
     */
    public function getFingerPrintId(): string
    {
        return $this->fingerPrintId;
    }
}
