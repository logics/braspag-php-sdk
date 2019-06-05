<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-04
 * Time: 15:21
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braspag\API;

/**
 * Class Product
 * @package Braspag\API
 */
class Product implements BraspagSerializable
{
    /** @var string */
    private $name;

    /** @var integer */
    private $quantity;

    /** @var string */
    private $sku;

    /** @var integer */
    private $unitPrice;

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->name = isset($data->Name) ? $data->Name : '';
        $this->quantity = isset($data->Quantity) ? $data->Quantity : '';
        $this->sku = isset($data->Sku) ? $data->Sku : '';
        $this->unitPrice = isset($data->UnitPrice) ? $data->UnitPrice : '';
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Product
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return Product
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    /**
     * @param int $unitPrice
     * @return Product
     */
    public function setUnitPrice(int $unitPrice): self
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }
}
