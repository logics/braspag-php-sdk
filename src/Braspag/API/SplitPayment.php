<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-01
 * Time: 13:23
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braspag\API;

use phpDocumentor\Reflection\Types\Integer;

/**
 * Class SplitPayment
 * @package Braspag\API
 */
class SplitPayment implements BraspagSerializable
{
    /**
     * MerchantId (Identificador) do Subordinado
     * @var string
     */
    private $subordinateMerchantId;

    /**
     * Parte do valor total da transação referente a participação do Subordinado, em centavos.
     * @var integer
     */
    private $amount;

    /**
     * @var Fares
     */
    private $fares;

    /**
     * Geralmente preenchido no retorno da API
     * @var Split[]
     */
    private $splits;

    /**
     * Geralmente preenchido no retorno da API
     * @var Split[]
     */
    private $voidedSplits;

    /**
     * SplitPayment constructor.
     * @param string|null $subordinateMerchantId  MerchantId (Identificador) do Subordinado.
     * @param int $amount Parte do valor total da transação referente a participação do Subordinado, em centavos.
     * @param float $mdr MDR(%) do Marketplace a ser descontado do valor referente a participação do Subordinado
     * @param int $fee Tarifa Fixa(R$) a ser descontada do valor referente a participação do Subordinado, em centavos.
     */
    public function __construct(string $subordinateMerchantId = null, int $amount = 0, float $mdr = 0.0, int $fee = 0)
    {
        $this->subordinateMerchantId = $subordinateMerchantId;
        $this->amount = $amount;
        $this->fares = new Fares($mdr, $fee);
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->subordinateMerchantId = isset($data->SubordinateMerchantId) ? $data->SubordinateMerchantId : null;
        $this->amount = isset($data->Amount) ? $data->Amount : null;

        if (isset($data->Fares)) {
            $this->fares = new Fares();
            $this->fares->populate($data->Fares);
        }

        if (isset($data->Splits) && is_array($data->Splits)) {
            $this->splits = [];

            foreach ($data->Splits as $splitData) {
                $split = new Split();
                $split->populate($splitData);
                $this->splits[] = $split;
            }
        }

        if (isset($data->VoidedSplits) && is_array($data->VoidedSplits)) {
            $this->voidedSplits = [];

            foreach ($data->VoidedSplits as $splitData) {
                $split = new Split();
                $split->populate($splitData);
                $this->voidedSplits[] = $split;
            }
        }
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * @return string
     */
    public function getSubordinateMerchantId(): string
    {
        return $this->subordinateMerchantId;
    }

    /**
     * @param string $subordinateMerchantId
     * @return SplitPayment
     */
    public function setSubordinateMerchantId(string $subordinateMerchantId): self
    {
        $this->subordinateMerchantId = $subordinateMerchantId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return SplitPayment
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return Fares
     */
    public function getFares(): Fares
    {
        return $this->fares;
    }

    /**
     * @param Fares $fares
     * @return SplitPayment
     */
    public function setFares(Fares $fares): self
    {
        $this->fares = $fares;
        return $this;
    }

    /**
     * @return Split[]
     */
    public function getSplits(): ?array
    {
        return $this->splits;
    }

    /**
     * @param Split[] $splits
     * @return SplitPayment
     */
    public function setSplits(?array $splits): self
    {
        $this->splits = $splits;
        return $this;
    }

    /**
     * @return Split[]
     */
    public function getVoidedSplits(): ?array
    {
        return $this->voidedSplits;
    }

    /**
     * @param Split[] $voidedSplits
     * @return SplitPayment
     */
    public function setVoidedSplits(?array $voidedSplits): self
    {
        $this->voidedSplits = $voidedSplits;
        return $this;
    }
}
