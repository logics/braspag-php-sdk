<?php

namespace Braspag\API;

class Environment implements \Braspag\Environment
{
    private $api;

    private $apiQuery;

    /**
     * The environment constructor.
     *
     * @param $api
     * @param $apiQuery
     */
    private function __construct($api, $apiQuery)
    {
        $this->api = $api;
        $this->apiQuery = $apiQuery;
    }

    /**
     * @return Environment
     */
    public static function sandbox(): self
    {
        $api = 'https://apisandbox.cieloecommerce.cielo.com.br/';
        $apiQuery = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/';

        return new Environment($api, $apiQuery);
    }

    /**
     * @return Environment
     */
    public static function production(): self
    {
        $api = 'https://api.cieloecommerce.cielo.com.br/';
        $apiQuery = 'https://apiquery.cieloecommerce.cielo.com.br/';

        return new Environment($api, $apiQuery);
    }

    /**
     * Gets the environment's Api URL
     *
     * @return string the Api URL
     */
    public function getApiUrl(): string
    {
        return $this->api;
    }

    /**
     * Gets the environment's Api Query URL
     *
     * @return string Api Query URL
     */
    public function getApiQueryURL(): string
    {
        return $this->apiQuery;
    }
}