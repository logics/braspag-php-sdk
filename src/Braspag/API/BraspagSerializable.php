<?php

namespace Braspag\API;

interface BraspagSerializable extends \JsonSerializable
{
    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data);
}
