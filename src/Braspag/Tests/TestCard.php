<?php
namespace Braspag\Tests;

use Braspag\API\Braspag;
use Braspag\API\CreditCard;
use Braspag\API\Environment;
use Braspag\API\Request\BraspagRequestException;
use Braspag\Authenticator;

class TestCard extends AuthenthicatedTest
{
    /**
     * @throws BraspagRequestException
     */
    public function testTokenizeCard()
    {
        // Cria uma instância do cartão para enviá-lo à Cielo
        $card = new CreditCard();
        $card->setCustomerName('Fulano de Tal');
        $card->setCardNumber('4024007153763191');
        $card->setHolder('Fulano d. Tal');
        $card->setExpirationDate('09/2020');
        $card->setBrand('Master');

        $auth = new Authenticator(self::CLIENT_SECRET, self::MERCHANT_ID, self::MERCHANT_KEY);

        $card = Braspag::shared($auth, Environment::sandbox())->tokenizeCard($card);

        // Get the token
        $cardToken = $card->getCardToken();

        $this->assertNotNull($cardToken);
    }
}
