<?php

/*
 * This file is part of the tarlanpayments/gw-client package.
 *
 * (c) Tarlan Payments
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TarlanPayments\Gateway\Operations\Info;

use PHPUnit\Framework\TestCase;
use TarlanPayments\Gateway\DataSets\DataSet;
use TarlanPayments\Gateway\DataSets\Info;
use TarlanPayments\Gateway\Validator\Validator;

class RecurrentsTest extends TestCase
{
    public function testSuccess()
    {
        $expected = [
            DataSet::COMMAND_DATA_GATEWAY_TRANSACTION_IDS => ['123'],
            DataSet::COMMAND_DATA_MERCHANT_TRANSACTION_IDS => ['123'],
        ];

        $status = new Recurrents(new Validator(), new Info());
        $status->info()
            ->setGatewayTransactionIDs(['123'])
            ->setMerchantTransactionIDs(['123']);

        $req = $status->build();

        $this->assertEquals("POST", $req->getMethod());
        $this->assertEquals("/recurrents", $req->getPath());
        $this->assertEquals($expected, $req->getData());
    }
}
