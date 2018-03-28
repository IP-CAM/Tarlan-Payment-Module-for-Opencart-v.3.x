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
use TarlanPayments\Gateway\Interfaces\OperationInterface;
use TarlanPayments\Gateway\Operations\Operation;
use TarlanPayments\Gateway\Validator\Validator;

/**
 * Class Reversal.
 *
 * This class describes dataset to perform REVERSAL request.
 * Refer to official documentation for more information about REVERSAL request.
 */
class Reversal extends Operation implements OperationInterface
{
    /**
     * {@inheritdoc}
     */
    protected $path = '/reversal';

    /**
     * {@inheritdoc}
     */
    protected $method = 'POST';

    /**
     * {@inheritdoc}
     */
    protected $mandatoryFields = [
        DataSet::COMMAND_DATA_GATEWAY_TRANSACTION_ID,
    ];

    /**
     * @var Command
     */
    private $command;

    /**
     * Refund constructor.
     * @param Validator $validator
     * @param Command   $command
     */
    public function __construct(Validator $validator, Command $command)
    {
        $this->validator = $validator;

        $this->command = $command;

        $this->dataSets = [$this->command];
    }

    /**
     * @return Command
     */
    public function command()
    {
        return $this->command;
    }
}
