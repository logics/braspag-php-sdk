<?php

namespace Braspag;

class AccessToken implements \JsonSerializable
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $tokenType;

    /**
     * @var integer
     */
    private $expiresIn;

    public function populate(\stdClass $data)
    {
        $this->token = isset($data->access_token) ? $data->access_token : null;
        $this->tokenType = isset($data->token_type) ? $data->token_type : null;
        $this->expiresIn = isset($data->expires_in) ? $data->expires_in : null;
    }

    /**
     * @param $json
     *
     * @return self
     */
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $accessToken = new AccessToken();
        $accessToken->populate($object);

        return $accessToken;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
}
