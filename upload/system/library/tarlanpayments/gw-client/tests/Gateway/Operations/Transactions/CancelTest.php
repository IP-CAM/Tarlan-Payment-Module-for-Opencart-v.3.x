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
use TarlanPayments\Gateway\Exceptions\ValidatorException;
use TarlanPayments\Gateway\Validator\Validator;

class CancelTest extends TestCase
{
    public function testCancelSuccess()
    {
        $expected = [
            DataSet::COMMAND_DATA_GATEWAY_TRANSACTION_ID => "qwe",
            DataSet::COMMAND_DATA_FORM_ID => "zxc",
            DataSet::COMMAND_DATA_TERMINAL_MID => "asd",
        ];

        $cancel = new Cancel(new Validator(), new Command());

        $cancel->command()
            ->setGatewayTransactionID("qwe")
            ->setFormID("zxc")
            ->setTerminalMID("asd");

        $req = $cancel->build();

        $this->assertEquals("POST", $req->getMethod());
        $this->assertEquals("/cancel", $req->getPath());
        $this->assertEquals($expected, $req->getData());
    }

    public function testCancelValidatorException()
    {
        $this->expectException(ValidatorException::class);

        $cancel = new Cancel(new Validator(), new Command());

        $cancel->command()
            ->setFormID("zxc")
            ->setTerminalMID("asd");

        $cancel->build();
    }
}
