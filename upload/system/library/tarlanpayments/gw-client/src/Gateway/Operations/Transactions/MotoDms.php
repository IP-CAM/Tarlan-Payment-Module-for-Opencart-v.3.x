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

use TarlanPayments\Gateway\DataSets\Customer;
use TarlanPayments\Gateway\DataSets\DataSet;
use TarlanPayments\Gateway\DataSets\Money;
use TarlanPayments\Gateway\DataSets\Order;
use TarlanPayments\Gateway\DataSets\PaymentMethod;
use TarlanPayments\Gateway\DataSets\System;
use TarlanPayments\Gateway\Interfaces\OperationInterface;
use TarlanPayments\Gateway\Operations\Operation;
use TarlanPayments\Gateway\Validator\Validator;

/**
 * Class MotoDms.
 *
 * This class describes dataset to perform MOTO DMS request.
 * Refer to official documentation for more information about MOTO DMS request.
 */
class MotoDms extends Operation implements OperationInterface
{
    /**
     * {@inheritdoc}
     */
    protected $path = '/moto/dms';

    /**
     * {@inheritdoc}
     */
    protected $method = 'POST';

    /**
     * {@inheritdoc}
     */
    protected $mandatoryFields = [
        DataSet::PAYMENT_METHOD_DATA_PAN,
        DataSet::PAYMENT_METHOD_DATA_EXPIRE,
        DataSet::MONEY_DATA_AMOUNT,
        DataSet::MONEY_DATA_CURRENCY,
    ];

    /**
     * @var PaymentMethod
     */
    private $paymentMethod;

    /**
     * @var Money
     */
    private $money;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var System
     */
    private $system;

    /**
     * DmsHold constructor.
     * @param Validator     $validator
     * @param PaymentMethod $paymentMethod
     * @param Money         $money
     * @param Customer      $customer
     * @param Order         $order
     * @param System        $system
     */
    public function __construct(Validator $validator, PaymentMethod $paymentMethod, Money $money, Customer $customer, Order $order, System $system)
    {
        $this->validator = $validator;

        $this->paymentMethod = $paymentMethod;
        $this->money = $money;
        $this->customer = $customer;
        $this->order = $order;
        $this->system = $system;

        $this->dataSets = [$this->paymentMethod, $this->money, $this->customer, $this->order, $this->system];
    }

    /**
     * @return PaymentMethod
     */
    public function paymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return Money
     */
    public function money()
    {
        return $this->money;
    }

    /**
     * @return Customer
     */
    public function customer()
    {
        return $this->customer;
    }

    /**
     * @return Order
     */
    public function order()
    {
        return $this->order;
    }

    /**
     * @return System
     */
    public function system()
    {
        return $this->system;
    }
}
