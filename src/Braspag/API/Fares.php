<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-01
 * Time: 13:41
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braspag\API;

/**
 * Class Fares
 * @package Braspag\API
 */
class Fares implements BraspagSerializable
{
    /**
     * MDR(%) do Marketplace a ser descontado do valor referente a participação do Subordinado
     * @var float
     */
    private $mdr;

    /**
     * Tarifa Fixa(R$) a ser descontada do valor referente a participação do Subordinado, em centavos.
     * @var integer
     */
    private $fee;

    public function __construct(float $mdr = null, int $fee = null)
    {
        $this->mdr = $mdr;
        $this->fee = $fee;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->mdr = isset($data->Mdr) ? $data->Mdr : null;
        $this->fee = isset($data->Fee) ? $data->Fee : null;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return float
     */
    public function getMdr(): float
    {
        return $this->mdr;
    }

    /**
     * @param float $mdr
     * @return Fares
     */
    public function setMdr(float $mdr): self
    {
        $this->mdr = $mdr;
        return $this;
    }

    /**
     * @return int
     */
    public function getFee(): int
    {
        return $this->fee;
    }

    /**
     * @param int $fee
     * @return Fares
     */
    public function setFee(int $fee): self
    {
        $this->fee = $fee;
        return $this;
    }
}
