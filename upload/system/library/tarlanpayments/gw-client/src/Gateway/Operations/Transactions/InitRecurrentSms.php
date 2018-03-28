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

/**
 * Class InitRecurrentSms.
 *
 * This class describes dataset to perform Init Recurrent SMS request.
 * Refer to official documentation for more information about Init Recurrent SMS request.
 */
class InitRecurrentSms extends Sms
{
    /**
     * {@inheritdoc}
     */
    protected $path = '/recurrent/sms/init';
}
