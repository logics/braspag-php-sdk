<?php
/**
 * This is part of Braspag SDK PHP.
 *
 * @author: Romeu Godoi <romeu@logics.com.br>
 * Date: 2019-06-01
 * Time: 14:16
 *
 * @copyright Copyright (C) 2019.
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Braspag\Tests;

use Braspag\API\Braspag;
use Braspag\API\Cart;
use Braspag\API\CreditCard;
use Braspag\API\Customer;
use Braspag\API\Environment;
use Braspag\API\FraudAnalysis;
use Braspag\API\Payment;
use Braspag\API\Product;
use Braspag\API\Request\BraspagRequestException;
use Braspag\API\Sale;
use Braspag\API\SplitPayment;
use Braspag\Authenticator;

class TestSale extends AuthenthicatedTest
{
    public function testSplitSale()
    {
        try {
            $auth = new Authenticator(self::CLIENT_SECRET, self::MERCHANT_ID, self::MERCHANT_KEY);
            $auth->authenticate(Environment::sandbox());

            // Crie uma instância de Sale informando o ID do pedido na loja
            $sale = new Sale('123');

            // Crie uma instância de Customer informando o nome do cliente
            $customer = (new Customer('Teste Accept'))
                ->setEmail('teste@teste.com.br')
                ->setIdentity('18160361106')
                ->setIdentityType('CPF')
            ;

            $sale->setCustomer($customer);

            // Produtos
            $products[] = (new Product())
                ->setName('Produto Teste')
                ->setSku('123')
                ->setQuantity(1)
                ->setUnitPrice(15700)
            ;

            $cart = new Cart($products);
            $fraudAnalysis = new FraudAnalysis("123456654322", 15700, $cart);

            // Crie a regra de split
            $splitPayments = [
                new SplitPayment(self::SUBORDINATE_MERCHANT_ID, 15700, 3, 10)
            ];

            // Crie uma instância de Payment informando o valor do pagamento sem separador de decimais
            $payment = $sale->payment(15700, 1, $splitPayments);

            // Informa os dados de análise de fraude
            $payment->setFraudAnalysis($fraudAnalysis);

            // Crie uma instância de Credit Card utilizando os dados de teste
            // esses dados estão disponíveis no manual de integração
            $payment->creditCard("123", CreditCard::VISA)
                ->setExpirationDate("12/2019")
                ->setCardNumber("4481530710186111")
                ->setHolder("Teste Accept")
            ;

            // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
            $braspag = Braspag::shared($auth, Environment::sandbox());

            $sale = $braspag->createSale($sale);

            // Com a venda criada na Braspag, já temos o ID do pagamento, TID e demais
            // dados retornados pela Braspag
            $payment = $sale->getPayment();

            $this->assertEquals(Payment::STATUS_AUTHORIZED, $payment->getStatus());
        } catch (BraspagRequestException $e) {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $this->throwException($e);
        }
    }
}
