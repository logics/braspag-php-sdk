# Braspag SDK PHP

SDK de integração com a API da Braspag. 

![GitHub release](https://img.shields.io/github/release/logics/braspag-php-sdk.svg?color=cadetblue)
![PHP from Packagist](https://img.shields.io/packagist/php-v/logicssoftware/braspag-php-sdk.svg)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--4-orange.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)
![Packagist](https://img.shields.io/packagist/l/logicssoftware/braspag-php-sdk.svg?color=yellow)
![Travis (.org) branch](https://img.shields.io/travis/logics/braspag-php-sdk/master.svg)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/logics/braspag-php-sdk.svg)
## Recursos
Este SDK contempla tanto o modelo de pagamento com split (`Marketplace`), quanto o pagamento direto.

#### Recursos testados:

* [x] [Tokenização de cartão](#card-tokenization).
* [X] [Pagamentos por cartão de crédito](#split-de-pagamentos---marketplace).
    * [X] [Split de pagamentos com regras definidas](#split-de-pagamentos---marketplace).
* [X] [Consulta de pagamentos](#consulta-de-pagamentos).
* [X] [Cancelamento de pagamento](#cancelamento-de-pagamento).
* [ ] Pagamentos recorrentes.
    * [ ] Com autorização na primeira recorrência.
    * [ ] Com autorização a partir da primeira recorrência.
* [ ] Pagamentos por cartão de débito.
* [ ] Pagamentos por boleto.
* [ ] Pagamentos por transferência eletrônica.


## Observações

Caso seha necessário qualquer tipo de redirecionamento do usuário, 
o desenvolvedor deverá utilizar o SDK para gerar o pagamento e, com o link retornado pela Braspag, 
providenciar o redirecionamento do usuário.

Algumas funcionalidades ainda não foram testadas, por isso ainda não estão marcadas em [`Recursos`](#recursos).

Disposto a ajudar? Faça um fork e envie um pull request com testes e build ok.  

## Dependências

* PHP >= 7.1

## Instalando o SDK
Caso ainda não possua o Composer instalado, siga as instruções em [getcomposer.org](https://getcomposer.org).

Se já possui um arquivo `composer.json`, basta executar diretamente em seu terminal:

```
composer require "logicssoftware/braspag-php-sdk"
```

## Exemplos de uso

### Card Tokenization:
Caso não queira guardar os dados sensíveis do cartão, e evitar ter que implementar uma PCI Compliance, você pode gerar um token do cartão para usar posteriormente nas transações:

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
$card = Braspag::shared($auth, Environment::sandbox())->tokenizeCard($card);

// Get the card token
$cardToken = $card->getCardToken();
```

### Split de Pagamentos - (Marketplace)
Caso queira efetivar um pagamento com split transacional:

```php
<?php
require 'vendor/autoload.php';

use Braspag\API\Braspag;
use Braspag\API\Cart;
use Braspag\API\CreditCard;
use Braspag\API\Customer;
use Braspag\API\Environment;
use Braspag\API\FraudAnalysis;
use Braspag\API\Product;
use Braspag\API\Sale;
use Braspag\API\SplitPayment;
use Braspag\Authenticator;
use Braspag\API\Payment;

$auth = new Authenticator('CLIENT_SECRET', 'MERCHANT_ID', 'MERCHANT_KEY');
$auth->authenticate(Environment::sandbox());

// Crie uma instância de Sale informando o ID do pedido na loja
$sale = new Sale('123');

// Crie uma instância de Customer informando os dados do cliente
$customer = (new Customer('Teste Accept'))
    ->setEmail('teste@teste.com.br')
    ->setIdentity('11111111111')
    ->setIdentityType('CPF')
;

$sale->setCustomer($customer);

// Define os Produtos
$products[] = (new Product())
    ->setName('Produto Teste')
    ->setSku('123')
    ->setQuantity(1)
    ->setUnitPrice(15700)
;

$cart = new Cart($products);

/**
 * Informa os dados para análise anti-fraude (obrigatório non caso de Marketplace): 
 * fingerPrintId, valor, Cart e MerchantDefinedFields - esse último precisa ter ao menos o item 1 e 4 da tabela:
 * @see https://braspag.github.io//manual/antifraude#tabela-31-merchantdefineddata-(cybersource)
 */
$fraudAnalysis = new FraudAnalysis(
    "123456654322",
    15700,
    $cart,
    ['1' => 'Guest', '4' => 'Web']
);

// Defina as regras de split dos subordinados
$splitPayments = [
    new SplitPayment('SUBORDINATE_MERCHANT_ID', 15700, 3, 10)
];

// Crie uma instância de Payment informando o valor do pagamento sem separador de decimais
$payment = $sale->payment(15700, 1, $splitPayments);

// Informa os dados de análise de fraude
$payment->setFraudAnalysis($fraudAnalysis);

// Você pode definir a captura automatica, ou fazê-la depois
$payment->setCapture(true);

// Crie uma instância de Credit Card utilizando os dados de teste
// esses dados estão disponíveis no manual de integração
$payment->creditCard("123", CreditCard::VISA)
    ->setExpirationDate("12/2019")
    ->setCardNumber("0000000000000001")
    ->setHolder("Teste Accept")
;

// Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
$braspag = Braspag::shared($auth, Environment::sandbox());

$sale = $braspag->createSale($sale);

// Com a venda criada na Braspag, já temos o ID do pagamento, TID e demais
// dados retornados pela Braspag
$payment = $sale->getPayment();

// Com o Payment você pode verificar o status 
if ($payment->getStatus() == Payment::STATUS_AUTHORIZED) {
    // Usar o Payment ID, TID, etc
    $paymentId = $payment->getPaymentId();
}
```

### Consulta de pagamentos
Caso queira buscar uma venda para pegar os dados de um pagamento direto na Braspag:

```php
<?php
require 'vendor/autoload.php';

use Braspag\API\Braspag;
use Braspag\API\Environment;
use Braspag\Authenticator;

// Configure os tokens de autenticação (adquiridos junto à Braspag)
$auth = new Authenticator('CLIENT_SECRET', 'MERCHANT_ID', 'MERCHANT_KEY');

// Consulta a venda informando o PaymentId
$sale = Braspag::shared($auth, Environment::sandbox())->getSale(123304883);
$payment = $sale->getPayment();
```

### Cancelamento de pagamento
Caso queira efetivar o cancelamento total de um pagamento:

```php
<?php
require 'vendor/autoload.php';

use Braspag\API\Braspag;
use Braspag\API\Payment;
use Braspag\API\Environment;
use Braspag\Authenticator;

// Configure os tokens de autenticação (adquiridos junto à Braspag)
$auth = new Authenticator('CLIENT_SECRET', 'MERCHANT_ID', 'MERCHANT_KEY');

// Solicita o cancelamento do pagamento informando o PaymentId
$payment = Braspag::shared($auth, Environment::sandbox())->cancelSale(123304883);

// Verifica se tudo ocorreu bem
$success = $payment->getStatus() == Payment::STATUS_VOIDED;
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

Para mais informações sobre a integração com a API da Braspag, vide o manual em: [Split de Pagamentos](https://braspag.github.io/manual/split-pagamentos-braspag) | [Pagador](https://braspag.github.io/manual/braspag-pagador)

##
Inspired by [Cielo SDK](https://github.com/DeveloperCielo/API-3.0-PHP).