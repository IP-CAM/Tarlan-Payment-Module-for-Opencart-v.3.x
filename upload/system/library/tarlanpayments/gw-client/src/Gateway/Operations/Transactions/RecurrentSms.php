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

use TarlanPayments\Gateway\DataSets\Command;
use TarlanPayments\Gateway\DataSets\DataSet;
use TarlanPayments\Gateway\DataSets\Money;
use TarlanPayments\Gateway\Interfaces\OperationInterface;
use TarlanPayments\Gateway\Operations\Operation;
use TarlanPayments\Gateway\Validator\Validator;

/**
 * Class RecurrentSms.
 *
 * This class describes dataset to perform RECURRENT SMS request.
 * Refer to official documentation for more information about RECURRENT SMS request.
 */
class RecurrentSms extends Operation implements OperationInterface
{
    /**
     * {@inheritdoc}
     */
    protected $path = '/recurrent/sms';

    /**
     * {@inheritdoc}
     */
    protected $method = 'POST';

    /**
     * {@inheritdoc}
     */
    protected $mandatoryFields = [
        DataSet::COMMAND_DATA_GATEWAY_TRANSACTION_ID,
        DataSet::MONEY_DATA_AMOUNT,
    ];

    /**
     * @var Money
     */
    private $money;

    /**
     * @var Command
     */
    private $command;

    /**
     * RecurrentSms constructor.
     * @param Validator $validator
     * @param Money     $money
     * @param Command   $command
     */
    public function __construct(Validator $validator, Money $money, Command $command)
    {
        $this->validator = $validator;

        $this->money = $money;
        $this->command = $command;

        $this->dataSets = [$this->money, $this->command];
    }

    /**
     * @return Command
     */
    public function command()
    {
        return $this->command;
    }

    /**
     * @return Money
     */
    public function money()
    {
        return $this->money;
    }
}
