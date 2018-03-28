<?php declare(strict_types = 1);

/*
 * This file is part of the tarlanpayments/gw-client package.
 *
 * (c) Tarlan Payments
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TarlanPayments\Gateway;

use TarlanPayments\Gateway\DataSets\Auth;
use TarlanPayments\Gateway\DataSets\Command;
use TarlanPayments\Gateway\DataSets\Customer;
use TarlanPayments\Gateway\DataSets\Info;
use TarlanPayments\Gateway\DataSets\Money;
use TarlanPayments\Gateway\DataSets\Order;
use TarlanPayments\Gateway\DataSets\PaymentMethod;
use TarlanPayments\Gateway\DataSets\System;
use TarlanPayments\Gateway\Exceptions\ValidatorException;
use TarlanPayments\Gateway\Http\Client\Client;
use TarlanPayments\Gateway\Http\Request;
use TarlanPayments\Gateway\Http\Transport\Curl;
use TarlanPayments\Gateway\Interfaces\HttpClientInterface;
use TarlanPayments\Gateway\Interfaces\OperationInterface;
use TarlanPayments\Gateway\Interfaces\ResponseInterface;
use TarlanPayments\Gateway\Operations\Info\History;
use TarlanPayments\Gateway\Operations\Info\Result;
use TarlanPayments\Gateway\Operations\Info\Status;
use TarlanPayments\Gateway\Operations\Transactions\Cancel;
use TarlanPayments\Gateway\Operations\Transactions\Credit;
use TarlanPayments\Gateway\Operations\Transactions\DmsCharge;
use TarlanPayments\Gateway\Operations\Transactions\DmsHold;
use TarlanPayments\Gateway\Operations\Transactions\InitRecurrentDms;
use TarlanPayments\Gateway\Operations\Transactions\InitRecurrentSms;
use TarlanPayments\Gateway\Operations\Transactions\MotoDms;
use TarlanPayments\Gateway\Operations\Transactions\MotoSms;
use TarlanPayments\Gateway\Operations\Transactions\P2P;
use TarlanPayments\Gateway\Operations\Transactions\RecurrentDms;
use TarlanPayments\Gateway\Operations\Transactions\RecurrentSms;
use TarlanPayments\Gateway\Operations\Transactions\Refund;
use TarlanPayments\Gateway\Operations\Transactions\Reversal;
use TarlanPayments\Gateway\Operations\Transactions\Sms;
use TarlanPayments\Gateway\Validator\Validator;

/**
 * Class Gateway.
 *
 * Gateway is the main class of the library.
 */
class Gateway
{
    /**
     * URL of the API.
     *
     * @var string
     */
    private $url = 'https://api.sandbox.tarlanpayments.kz/v3.0';

    /**
     * HTTP Client.
     *
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * Gateway constructor.
     *
     * If no custom URL is passed default will be used.
     * If you provide custom URL, it should be *without* trailing slash (/).
     *
     * @param string $url
     */
    public function __construct($url = '')
    {
        if ($url !== '') {
            $this->url = $url;
        }

        // set defaults
        $this->auth = new Auth();

        $transport = new Curl();
        $transport->setDefaultOptions();
        $this->httpClient = new Client($this->url, $transport);
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Authentication block setup.
     *
     * @return Auth
     */
    public function auth()
    {
        return $this->auth;
    }

    /**
     * SMS transaction request builder.
     *
     * SMS transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return Sms
     */
    public function createSms()
    {
        return new Sms(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
    }

    /**
     * DMS HOLD transaction request builder.
     *
     * DMS HOLD transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return DmsHold
     */
    public function createDmsHold()
    {
        return new DmsHold(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
    }

    /**
     * DMS CHARGE transaction request builder.
     *
     * DMS CHARGE transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return DmsCharge
     */
    public function createDmsCharge()
    {
        return new DmsCharge(new Validator(), new Money(), new Command());
    }

    /**
     * CANCEL transaction request builder.
     *
     * CANCEL transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return Cancel
     */
    public function createCancel()
    {
        return new Cancel(new Validator(), new Command());
    }

    /**
     * MOTO SMS transaction request builder.
     *
     * MOTO SMS transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return MotoSms
     */
    public function createMotoSms()
    {
        return new MotoSms(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
    }

    /**
     * MOTO DMS transaction request builder.
     *
     * MOTO DMS transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return MotoDms
     */
    public function createMotoDms()
    {
        return new MotoDms(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
    }

    /**
     * Credit transaction request builder.
     *
     * Credit transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return Credit
     */
    public function createCredit()
    {
        return new Credit(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
    }

    /**
     * P2P transaction request builder.
     *
     * P2P transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return P2P
     */
    public function createP2P()
    {
        return new P2P(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
    }

    /**
     * Init Recurrent SMS transaction request builder.
     *
     * Init Recurrent SMS transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return InitRecurrentSms
     */
    public function createInitRecurrentSms()
    {
        return new InitRecurrentSms(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
    }

    /**
     * Init Recurrent DMS HOLD transaction request builder.
     *
     * Init Recurrent DMS HOLD transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return InitRecurrentDms
     */
    public function createInitRecurrentDms()
    {
        return new InitRecurrentDms(new Validator(), new PaymentMethod(), new Money(), new Customer(), new Order(), new System());
    }

    /**
     * RECURRENT SMS transaction request builder.
     *
     * RECURRENT SMS transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return RecurrentSms
     */
    public function createRecurrentSms()
    {
        return new RecurrentSms(new Validator(), new Money(), new Command());
    }

    /**
     * RECURRENT DMS transaction request builder.
     *
     * RECURRENT DMS transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return RecurrentDms
     */
    public function createRecurrentDms()
    {
        return new RecurrentDms(new Validator(), new Money(), new Command());
    }

    /**
     * REFUND transaction request builder.
     *
     * REFUND transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return Refund
     */
    public function createRefund()
    {
        return new Refund(new Validator(), new Money(), new Command());
    }

    /**
     * REVERSAL transaction request builder.
     *
     * REVERSAL transaction request builder provide all
     * needed methods to prepare request.
     *
     * @return Reversal
     */
    public function createReversal()
    {
        return new Reversal(new Validator(), new Command());
    }

    /**
     * RESULT information request builder.
     *
     * RESULT information builder provide all
     * needed methods to prepare request.
     *
     * @return Result
     */
    public function createResult()
    {
        return new Result(new Validator(), new Info());
    }

    /**
     * HISTORY information request builder.
     *
     * HISTORY information builder provide all
     * needed methods to prepare request.
     *
     * @return History
     */
    public function createHistory()
    {
        return new History(new Validator(), new Info());
    }

    /**
     * STATUS information request builder.
     *
     * STATUS information builder provide all
     * needed methods to prepare request.
     *
     * @return Status
     */
    public function createStatus()
    {
        return new Status(new Validator(), new Info());
    }

    /**
     * Process prepares and apply provided request
     *
     * @param  Request           $request
     * @return ResponseInterface
     */
    public function process(Request $request)
    {
        $payload = $request->getPreparedData();

        if (empty($payload)) {
            $payload = $this->generatePayload(array_merge($this->auth->getRaw(), $request->getData()));
            $request->setPreparedData($payload);
        }

        return $this->httpClient->request($request->getMethod(), $request->getPath(), $payload);

    }

    /**
     * Generate request generates Request object filled with data for processing
     *
     * @param OperationInterface $operation
     * @throws ValidatorException
     * @return Request
     */
    public function generateRequest(OperationInterface $operation)
    {
        $req = $operation->build();

        $payload = $this->generatePayload(array_merge($this->auth->getRaw(), $req->getData()));

        $req->setPreparedData($payload);

        return $req;
    }

    /**
     * Generate JSON string payload for the request.
     *
     * @param array $mergedPayload
     * @return string
     */
    private function generatePayload(array $mergedPayload = [])
    {
        $finalPayload = [];
        foreach ($mergedPayload as $k => $v) {
            $finalPayload = array_merge_recursive($finalPayload, $this->setByPath($k, $v));
        }

        return json_encode($finalPayload);
    }

    /**
     * Create array by provided path.
     *
     * @param  string $path  JSON path
     * @param  mixed  $value Value to be by JSON path
     * @return array
     */
    private function setByPath($path, $value)
    {
        $storage = [];

        $aPath = $this->explode($path);

        $tmp = &$storage;
        foreach ($aPath as $path) {
            $tmp = &$tmp[$path];
        }

        $tmp = $value;
        unset($tmp);

        return $storage;
    }

    /**
     * Explode JSON path.
     *
     * @param  string $path Path as "some.json.path"
     * @return array
     */
    private function explode(string $path): array
    {
        $sPattern = '/\w+((\.\w+)+)?/';

        if (!preg_match($sPattern, $path)) {
        }

        return explode('.', $path);
    }
}
