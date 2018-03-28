<?php declare(strict_types = 1);

/*
 * This file is part of the tarlanpayments/gw-client package.
 *
 * (c) Tarlan Payments
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TarlanPayments\Gateway\Interfaces;

use TarlanPayments\Gateway\Exceptions\ValidatorException;
use TarlanPayments\Gateway\Http\Request;

/**
 * Interface OperationInterface.
 *
 * All defined operations should implement this interface.
 */
interface OperationInterface
{
    /**
     * Build build Request object
     *
     * @throws ValidatorException
     * @return Request
     */
    public function build();
}
