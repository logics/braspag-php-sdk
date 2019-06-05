<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-04
 * Time: 15:19
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braspag\API;

/**
 * Class Cart
 * @package Braspag\API
 */
class Cart implements BraspagSerializable
{
    private $isGift = false;
    private $returnsAccepted = true;

    /** @var Product[] */
    private $items;

    /**
     * Cart constructor.
     * @param Product[] $items
     */
    public function __construct($items = null)
    {
        $this->items = $items;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        $this->isGift = isset($data->IsGift) ? $data->IsGift : false;

        $items = [];

        if (isset($data->Items) && is_array($data->Items)) {
            foreach ($data->Items as $itemData) {
                $item = (new Product())->populate($itemData);
                $items[] = $item;
            }
        }

        $this->items = $items;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return bool
     */
    public function isGift(): bool
    {
        return $this->isGift;
    }

    /**
     * @param bool $isGift
     * @return Cart
     */
    public function setIsGift(bool $isGift): self
    {
        $this->isGift = $isGift;
        return $this;
    }

    /**
     * @return Product[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Product[] $items
     * @return Cart
     */
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReturnsAccepted(): bool
    {
        return $this->returnsAccepted;
    }

    /**
     * @param bool $returnsAccepted
     * @return Cart
     */
    public function setReturnsAccepted(bool $returnsAccepted): self
    {
        $this->returnsAccepted = $returnsAccepted;
        return $this;
    }
}
