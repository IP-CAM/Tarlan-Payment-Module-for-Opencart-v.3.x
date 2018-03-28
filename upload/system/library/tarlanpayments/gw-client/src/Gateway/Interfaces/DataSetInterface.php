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

/**
 * Interface DataSetInterface.
 *
 * All DataSets should implement this interface.
 */
interface DataSetInterface
{
    /**
     * getRaw return raw data block,
     * that will be used during build procedure
     *
     * @return array
     */
    public function getRaw();
}
