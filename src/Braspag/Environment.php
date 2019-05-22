<?php

namespace Braspag;

interface Environment
{
    /**
     * @return string the Api URL
     */
    public function getApiUrl();

    /**
     * @return string the Api Query URL
     */
    public function getApiQueryURL();
}
