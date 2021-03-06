<?php declare(strict_types = 1);

/*
 * This file is part of the tarlanpayments/gw-client package.
 *
 * (c) Tarlan Payments
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TarlanPayments\Gateway\DataSets;

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testSuccess()
    {
        $expected = [
            DataSet::AUTH_DATA_ACCOUNT_ID => 1,
            DataSet::AUTH_DATA_SECRET_KEY => 'aaaa',
            DataSet::AUTH_DATA_SESSION_ID => 'foo',
        ];

        $auth = new Auth();
        $generated = $auth->setAccountID(1)
            ->setSecretKey('aaaa')
            ->setSessionID('foo')
            ->getRaw();

        $this->assertEquals($expected, $generated);
    }
}
