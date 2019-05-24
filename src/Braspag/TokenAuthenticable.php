<?php

namespace Braspag;

/**
 * Interface TokenAuthenticable
 * @package Braspag
 */
interface TokenAuthenticable
{
    /**
     * Lista de cabeçalhos header de authenticação
     *
     * @return array
     */
    public function getAuthenticationHeaders(): array;
}
