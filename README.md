# Braspag SDK PHP

SDK de integração com a API da Braspag, inspirado no SDK Cielo [API-3.0](https://github.com/DeveloperCielo/API-3.0-PHP).

## Recursos

* [x] Pagamentos por cartão de crédito.
    * [X] Split de pagamentos com regras definidas.
* [x] Consulta de pagamentos.
* [x] Tokenização de cartão.
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

Se já possui um arquivo `composer.json`, basta adicionar a seguinte dependência ao seu projeto:

```json
"require": {
    "romeugodoi/braspag-php-sdk": "^1.0"
}
```

Com a dependência adicionada ao `composer.json`, basta executar:

```
composer install
```

Alternativamente, você pode executar diretamente em seu terminal:

```
composer require "romeugodoi/braspag-php-sdk"
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
