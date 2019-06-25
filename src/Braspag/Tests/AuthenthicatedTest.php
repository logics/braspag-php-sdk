<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-05
 * Time: 15:07
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braspag\Tests;

use Braspag\Authenticator;
use PHPUnit\Framework\TestCase;

/**
 * Class AuthenthicatedTest
 * @package tests
 */
abstract class AuthenthicatedTest extends TestCase
{
    /**
     * Test Tokens
     */
    const MERCHANT_ID = '5674b112-306f-4487-bc12-2bb2945a02db';
    const MERCHANT_KEY = 'FCZTBCIBCXWZAWWOPNXBWWVFSZCKXBCHYOKYPNSN';
    const CLIENT_SECRET = '/z5oFepGQLnbnn+tSxaVTjvDJago9jX7CTlDlilebk4=';

    const SUBORDINATE_MERCHANT_ID = '66e7caad-5132-4e9c-ab11-35167ac261f8';
    const SUBORDINATE_MERCHANT_ID_2 = '3bd84c9c-0b55-47d2-b042-478990aa0dd3';

    private static $auth;

    /**
     * @return Authenticator
     */
    protected function getAuth(): Authenticator
    {
        if (!isset(self::$auth)) {
            self::$auth = new Authenticator(self::CLIENT_SECRET, self::MERCHANT_ID, self::MERCHANT_KEY);
        }

        return self::$auth;
    }
}
