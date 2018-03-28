<?php declare(strict_types = 1);

/*
 * This file is part of the tarlanpayments/gw-client package.
 *
 * (c) Tarlan Payments
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TarlanPayments\Gateway\Validator;

use PHPUnit\Framework\TestCase;
use TarlanPayments\Gateway\DataSets\DataSet;
use TarlanPayments\Gateway\Exceptions\ValidatorException;

class ValidatorTest extends TestCase
{
    public function testValidatorSuccess()
    {
        $validator = new Validator();

        $result = $validator->validate([
            DataSet::MONEY_DATA_AMOUNT,
            DataSet::MONEY_DATA_CURRENCY,
        ], [
            DataSet::MONEY_DATA_AMOUNT => 100,
            DataSet::MONEY_DATA_CURRENCY => "USD",
        ]);

        $this->assertTrue($result);
    }

    public function testValidatorThrowExceptionWhenFieldIsNotSet()
    {
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage("No value by \"data.money-data.currency\" is found.");

        $validator = new Validator();

        $validator->validate([
            DataSet::MONEY_DATA_AMOUNT,
            DataSet::MONEY_DATA_CURRENCY,
        ], [
            DataSet::MONEY_DATA_AMOUNT => 100,
        ]);
    }

    public function testValidatorThrowExceptionWhenTypeIsNotCorrect()
    {
        $this->expectException(ValidatorException::class);
        $this->expectExceptionMessage("Type of \"100\" should be \"integer\", but is \"string\"");

        $validator = new Validator();

        $validator->validate([
            DataSet::MONEY_DATA_AMOUNT,
            DataSet::MONEY_DATA_CURRENCY,
        ], [
            DataSet::MONEY_DATA_AMOUNT => '100',
            DataSet::MONEY_DATA_CURRENCY => 'USD',
        ]);
    }
}
