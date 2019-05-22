<?php

namespace Braspag\API\Request;

class BraspagRequestException extends \Exception
{
    private $braspagError;

    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getBraspagError(): BraspagError
    {
        return $this->braspagError;
    }

    public function setBraspagError(BraspagError $braspagError): self
    {
        $this->braspagError = $braspagError;
        return $this;
    }
}
