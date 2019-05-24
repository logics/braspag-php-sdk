# Braspag SDK PHP

SDK de integração com a API da Braspag, inspirado no SDK Cielo [API-3.0](https://github.com/DeveloperCielo/API-3.0-PHP).

[![Latest Stable Version](https://poser.pugx.org/romeugodoi/braspag-php-sdk/v/stable)](https://packagist.org/packages/romeugodoi/braspag-php-sdk)
[![License](https://poser.pugx.org/romeugodoi/braspag-php-sdk/license)](https://packagist.org/packages/romeugodoi/braspag-php-sdk)

## Recursos

* [x] Tokenização de cartão.
* [ ] Pagamentos por cartão de crédito.
    * [ ] Split de pagamentos com regras definidas.
* [ ] Consulta de pagamentos.
* [ ] Pagamentos recorrentes.
    * [ ] Com autorização na primeira recorrência.
    * [ ] Com autorização a partir da primeira recorrência.
* [ ] Pagamentos por cartão de débito.
* [ ] Pagamentos por boleto.
* [ ] Pagamentos por transferência eletrônica.
* [ ] Cancelamento de autorização.


## Limitações

Nos casos onde é necessário a autenticação ou qualquer tipo de redirecionamento do usuário, 
o desenvolvedor deverá utilizar o SDK para gerar o pagamento e, com o link retornado pela Braspag, 
providenciar o redirecionamento do usuário.

## Dependências

* PHP >= 7.0

## Instalando o SDK
Caso ainda não possua o Composer instalado, siga as instruções em [getcomposer.org](https://getcomposer.org).

Se já possui um arquivo `composer.json`, basta executar diretamente em seu terminal:

```
composer require "romeugodoi/braspag-php-sdk"
```

## Exemplos de uso

### Tokenização de cartão:
Caso não queira guardar os dados sensíveis do cartão, você pode gerar um token dele para uso nas transações:

```php
<?php
require 'vendor/autoload.php';

use Braspag\API\Braspag;
use Braspag\API\CreditCard;
use Braspag\API\Environment;
use Braspag\Authenticator;

// Cria uma instância do cartão para enviar à Cielo
$card = new CreditCard();
$card->setCustomerName('John Rambo');
$card->setCardNumber('0000000000000001');
$card->setHolder('John Rambo');
$card->setExpirationDate('09/2020');
$card->setBrand('Master');

// Configure os tokens de autenticação (adquiridos junto à Braspag)
$auth = new Authenticator('CLIENT_SECRET', 'MERCHANT_ID', 'MERCHANT_KEY');

// Solicita a tokenização do card
$card = (new Braspag($auth, Environment::sandbox()))->tokenizeCard($card);

// Get the card token
$cardToken = $card->getCardToken();
```


## Produtos e Bandeiras suportadas e suas constantes

| Bandeira         | Constante              | Crédito à vista | Crédito parcelado Loja | Débito | Voucher |
|------------------|------------------------|-----------------|------------------------|--------|---------|
| Visa             | CreditCard::VISA       | Sim             | Sim                    | Sim    | *Não*   |
| Master Card      | CreditCard::MASTERCARD | Sim             | Sim                    | Sim    | *Não*   |
| American Express | CreditCard::AMEX       | Sim             | Sim                    | *Não*  | *Não*   |
| Elo              | CreditCard::ELO        | Sim             | Sim                    | *Não*  | *Não*   |
| Diners Club      | CreditCard::DINERS     | Sim             | Sim                    | *Não*  | *Não*   |
| Discover         | CreditCard::DISCOVER   | Sim             | *Não*                  | *Não*  | *Não*   |
| JCB              | CreditCard::JCB        | Sim             | Sim                    | *Não*  | *Não*   |
| Aura             | CreditCard::AURA       | Sim             | Sim                    | *Não*  | *Não*   |


## Manual

Para mais informações sobre a integração com a API da Braspag, vide o manual em: [Split de Pagamentos](https://braspag.github.io/manual/split-pagamentos-braspag)
