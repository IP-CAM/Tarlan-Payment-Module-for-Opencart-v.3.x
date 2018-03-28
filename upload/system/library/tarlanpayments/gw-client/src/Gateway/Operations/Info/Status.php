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

use TarlanPayments\Gateway\DataSets\Info;
use TarlanPayments\Gateway\Interfaces\OperationInterface;
use TarlanPayments\Gateway\Operations\Operation;
use TarlanPayments\Gateway\Validator\Validator;

/**
 * Class Status.
 *
 * This class describes dataset to perform STATUS request.
 * Refer to official documentation for more information about STATUS request.
 */
class Status extends Operation implements OperationInterface
{
    /**
     * {@inheritdoc}
     */
    protected $method = 'POST';

    /**
     * {@inheritdoc}
     */
    protected $path = '/status';

    /**
     * @var Info
     */
    private $info;

    /**
     * Status constructor.
     *
     * @param Validator $validator
     * @param Info      $info
     */
    public function __construct(Validator $validator, Info $info)
    {
        $this->validator = $validator;
        $this->info = $info;

        $this->dataSets = [$this->info];
    }

    /**
     * @return Info
     */
    public function info(): Info
    {
        return $this->info;
    }
}
