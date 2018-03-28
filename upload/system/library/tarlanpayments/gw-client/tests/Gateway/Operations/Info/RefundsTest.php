<?php declare(strict_types = 1);

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

class RefundsTest extends TestCase
{
    public function testSuccess()
    {
        $expected = [
            DataSet::COMMAND_DATA_GATEWAY_TRANSACTION_IDS => ['123'],
            DataSet::COMMAND_DATA_MERCHANT_TRANSACTION_IDS => ['123'],
        ];

        $refunds = new Refunds(new Validator(), new Info());
        $refunds->info()
            ->setGatewayTransactionIDs(['123'])
            ->setMerchantTransactionIDs(['123']);

        $req = $refunds->build();

        $this->assertEquals("POST", $req->getMethod());
        $this->assertEquals("/refunds", $req->getPath());
        $this->assertEquals($expected, $req->getData());
    }
}
