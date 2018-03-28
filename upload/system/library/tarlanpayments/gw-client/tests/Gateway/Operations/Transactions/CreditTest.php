<?php declare(strict_types = 1);

/*
 * This file is part of the tarlanpayments/gw-client package.
 *
 * (c) Tarlan Payments
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TarlanPayments\Gateway\Operations\Transactions;

use PHPUnit\Framework\TestCase;
use TarlanPayments\Gateway\DataSets\Customer;
use TarlanPayments\Gateway\DataSets\DataSet;
use TarlanPayments\Gateway\DataSets\Money;
use TarlanPayments\Gateway\DataSets\Order;
use TarlanPayments\Gateway\DataSets\PaymentMethod;
use TarlanPayments\Gateway\DataSets\System;
use TarlanPayments\Gateway\Exceptions\ValidatorException;
use TarlanPayments\Gateway\Validator\Validator;

class CreditTest extends TestCase
{
    public function testCreditSuccess()
    {
        $expected = [
            DataSet::PAYMENT_METHOD_DATA_PAN => 'qwe123',
            DataSet::PAYMENT_METHOD_DATA_EXPIRE => '12/21',
            DataSet::MONEY_DATA_AMOUNT => 100,
            DataSet::MONEY_DATA_CURRENCY => 'USD',
        ];

        $order = new Credit(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
        $order->paymentMethod()
            ->setPAN('qwe123')
            ->setExpire('12/21');
        $order->money()
            ->setAmount(100)
            ->setCurrency('USD');

        $raw = $order->build();

        $this->assertEquals("POST", $raw->getMethod());
        $this->assertEquals("/credit", $raw->getPath());
        $this->assertEquals($expected, $raw->getData());
    }

    public function testCreditValidatorException()
    {
        $this->expectException(ValidatorException::class);

        $order = new Credit(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());

        $order->build();
    }

    public function testCreditInsideForm()
    {
        $order = new Credit(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
        $order->money()
            ->setAmount(100)
            ->setCurrency('EUR');

        $raw = $order->insideForm()->build();

        $this->assertEquals($raw->getPath(), '/credit');
    }
}
