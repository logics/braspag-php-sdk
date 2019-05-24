<?php
namespace tests;

use Braspag\API\Braspag;
use Braspag\API\CreditCard;
use Braspag\API\Environment;
use Braspag\API\Request\BraspagRequestException;
use Braspag\Authenticator;
use PHPUnit\Framework\TestCase;

class TestCard extends TestCase
{
    const MERCHANT_ID = '5674b112-306f-4487-bc12-2bb2945a02db';
    const MERCHANT_KEY = 'FCZTBCIBCXWZAWWOPNXBWWVFSZCKXBCHYOKYPNSN';
    const CLIENT_SECRET = '/z5oFepGQLnbnn+tSxaVTjvDJago9jX7CTlDlilebk4=';

    /**
     * @throws BraspagRequestException
     */
    public function testTokenizeCard()
    {
        // Cria uma instância do cartão para enviá-lo à Cielo
        $card = new CreditCard();
        $card->setCustomerName('Romeu Godoi');
        $card->setCardNumber('4024007153763191');
        $card->setHolder('Romeu Godoi');
        $card->setExpirationDate('09/2020');
        $card->setBrand('Master');

        $auth = new Authenticator(self::CLIENT_SECRET, self::MERCHANT_ID, self::MERCHANT_KEY);

        $card = (new Braspag($auth, Environment::sandbox()))->tokenizeCard($card);

        // Get the token
        $cardToken = $card->getCardToken();

        $this->assertNotNull($cardToken);
    }
}
