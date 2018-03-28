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
use TarlanPayments\Gateway\DataSets\Command;
use TarlanPayments\Gateway\DataSets\DataSet;
use TarlanPayments\Gateway\DataSets\Money;
use TarlanPayments\Gateway\Exceptions\ValidatorException;
use TarlanPayments\Gateway\Validator\Validator;

class RefundTest extends TestCase
{
    public function testRefundSuccess()
    {
        $expected = [
            DataSet::COMMAND_DATA_GATEWAY_TRANSACTION_ID => 'qwe123',
            DataSet::MONEY_DATA_AMOUNT => 100,
        ];

        $refund = new Refund(new Validator(), new Money(), new Command());

        $refund->command()->setGatewayTransactionID('qwe123');
        $refund->money()->setAmount(100);

        $res = $refund->build();

        $this->assertEquals("POST", $res->getMethod());
        $this->assertEquals("/refund", $res->getPath());
        $this->assertEquals($expected, $res->getData());
    }

    public function testRefundValidatorException()
    {
        $this->expectException(ValidatorException::class);

        $refund = new Refund(new Validator(), new Money(), new Command());

        $refund->build();
    }
}
