<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-11
 * Time: 10:35
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Braspag\API\CreditCard;
use Braspag\API\Environment;
use Braspag\Tests\AuthenthicatedTest;

class TestCurl extends AuthenthicatedTest
{
    public function testeCurl()
    {
        $env = Environment::sandbox();
        $url = $env->getCieloApiUrl() . '1/card/';

        $card = new CreditCard();
        $card->setCustomerName('Fulano de Tal');
        $card->setCardNumber('4024007153763191');
        $card->setHolder('Fulano d. Tal');
        $card->setExpirationDate('09/2020');
        $card->setBrand('Master');

        $headers = [
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'User-Agent: Braspag/1.0 PHP SDK',
            'RequestId: ' . uniqid(),
            'MerchantId: ' . self::MERCHANT_ID,
            'MerchantKey: ' . self::MERCHANT_KEY,
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_VERBOSE, true);

        $this->assertNotNull($curl);

        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);

        curl_setopt($curl, CURLOPT_POST, true);

        $postFields = json_encode($card);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);

        $headers[] = 'Content-Type: application/json';

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        var_dump($response);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        var_dump($statusCode);

        if (curl_errno($curl)) {
            throw new \RuntimeException('Curl error: ' . curl_error($curl));
        }

        $this->assertNotNull($response);

        curl_close($curl);
    }
}
