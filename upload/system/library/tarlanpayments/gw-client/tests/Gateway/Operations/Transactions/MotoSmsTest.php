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

class MotoSmsTest extends TestCase
{
    public function testMotoSmsSuccess()
    {
        $expected = [
            DataSet::PAYMENT_METHOD_DATA_PAN => '123',
            DataSet::PAYMENT_METHOD_DATA_EXPIRE => '12/21',
            DataSet::MONEY_DATA_AMOUNT => 100,
            DataSet::MONEY_DATA_CURRENCY => 'USD',
        ];

        $motoSms = new MotoSms(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
        $motoSms->money()->setAmount(100)->setCurrency('USD');
        $motoSms->paymentMethod()->setPAN('123')->setExpire('12/21');

        $raw = $motoSms->build();

        $this->assertEquals("POST", $raw->getMethod());
        $this->assertEquals("/moto/sms", $raw->getPath());
        $this->assertEquals($expected, $raw->getData());
    }

    public function testMotoSmsValidatorException()
    {
        $this->expectException(ValidatorException::class);

        $motoSms = new MotoSms(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());

        $motoSms->build();
    }
}
