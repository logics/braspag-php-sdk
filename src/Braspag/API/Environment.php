<?php

namespace Braspag\API;

class Environment implements \Braspag\Environment
{
    private $apiCielo;

    private $apiCieloQuery;

    private $apiSplit;

    private $apiAuth;

    /**
     * The environment constructor.
     *
     * @param string $apiCielo
     * @param string $apiCieloQuery
     * @param string $apiSplit
     * @param string $apiAuth
     */
    private function __construct($apiCielo, $apiCieloQuery, $apiSplit, $apiAuth)
    {
        $this->apiCielo = $apiCielo;
        $this->apiCieloQuery = $apiCieloQuery;
        $this->apiSplit = $apiSplit;
        $this->apiAuth = $apiAuth;
    }

    /**
     * @return Environment
     */
    public static function sandbox(): self
    {
        $apiCielo = 'https://apisandbox.cieloecommerce.cielo.com.br/';
        $apiCieloQuery = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/';
        $apiSplit = 'https://splitsandbox.braspag.com.br/';
        $apiAuth = 'https://authsandbox.braspag.com.br/';

        return new Environment($apiCielo, $apiCieloQuery, $apiSplit, $apiAuth);
    }

    /**
     * @return Environment
     */
    public static function production(): self
    {
        $apiCielo = 'https://api.cieloecommerce.cielo.com.br/';
        $apiCieloQuery = 'https://apiquery.cieloecommerce.cielo.com.br/';
        $apiSplit = 'https://split.braspag.com.br/';
        $apiAuth = 'https://auth.braspag.com.br/';

        return new Environment($apiCielo, $apiCieloQuery, $apiSplit, $apiAuth);
    }

    /**
     * Gets the environment's Api Cielo URL
     *
     * @return string the Api URL
     */
    public function getCieloApiUrl(): string
    {
        return $this->apiCielo;
    }

    /**
     * Gets the environment's Api Cielo Query URL
     *
     * @return string Api Query URL
     */
    public function getCieloApiQueryURL(): string
    {
        return $this->apiCieloQuery;
    }

    /**
     * @return string Api Split URL
     */
    public function getSplitApiUrl()
    {
        return $this->apiSplit;
    }

    /**
     * @return string Api OAuth2 URL
     */
    public function getApiAuthUrl()
    {
        return $this->apiAuth;
    }
}
