<?php

namespace Braspag\API\Request;

class BraspagError
{
    /** @var int */
    private $code;

    /** @var string */
    private $message;

    public function __construct(string $message, int $code)
    {
        $this->setMessage($message);
        $this->setCode($code);
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): self
    {
        $this->message = $message;
        return $this;
    }
}
