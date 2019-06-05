<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-01
 * Time: 13:34
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braspag\API;

/**
 * Class Split
 * @package Braspag\API
 */
class Split implements BraspagSerializable
{
    /**
     * MerchantId (Identificador) do Subordinado ou Marketplace
     * @var string
     */
    private $merchantId;

    /**
     * Parte do valor calculado da transação a ser recebido pelo Subordinado ou Marketplace,
     * já descontando todas as taxas (MDR e Tarifa Fixa)
     * @var integer
     */
    private $amount;

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->merchantId = isset($data->MerchantId) ? $data->MerchantId : null;
        $this->amount = isset($data->Amount) ? $data->Amount : null;
    }

    public function jsonSerialize()
    {
        $array = array_filter(get_object_vars($this));
        $array = array_combine(
            array_map('ucfirst', array_keys($array)),
            array_values($array)
        );

        return $array;
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    /**
     * @param string $merchantId
     * @return Split
     */
    public function setMerchantId(string $merchantId): self
    {
        $this->merchantId = $merchantId;
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
     * @return Split
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }
}
