<?php

namespace Braspag;

interface Environment
{
    /**
     * @return string the Cielo Api URL
     */
    public function getCieloApiUrl();

    /**
     * @return string the Cielo Api Query URL
     */
    public function getCieloApiQueryURL();

    /**
     * @return string Api Split URL
     */
    public function getSplitApiUrl();

    /**
     * @return string Api OAuth2 URL
     */
    public function getApiAuthUrl();
}
