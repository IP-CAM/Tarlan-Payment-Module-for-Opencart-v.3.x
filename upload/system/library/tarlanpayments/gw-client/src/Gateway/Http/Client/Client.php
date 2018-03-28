<?php declare(strict_types = 1);

/*
 * This file is part of the tarlanpayments/gw-client package.
 *
 * (c) Tarlan Payments
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TarlanPayments\Gateway\Http\Client;

use TarlanPayments\Gateway\Exceptions\RequestException;
use TarlanPayments\Gateway\Http\Response;
use TarlanPayments\Gateway\Interfaces\HttpClientInterface;
use TarlanPayments\Gateway\Interfaces\HttpTransportInterface;
use TarlanPayments\Gateway\Interfaces\ResponseInterface;

class Client implements HttpClientInterface
{
    /**
     * Full URL of the resource.
     *
     * @var string
     */
    private $url;

    /**
     * Transport which will actually do the request.
     *
     * @var HttpTransportInterface
     */
    private $transport;

    /**
     * Curl constructor.
     *
     * @param string                 $url           Full URL of the resource
     * @param HttpTransportInterface $httpTransport Transport
     */
    public function __construct($url, HttpTransportInterface $httpTransport)
    {
        $this->url = $url;
        $this->transport = $httpTransport;
    }

    /**
     * {@inheritdoc}
     */
    public function request(string $method, string $path, string $body): ResponseInterface
    {
        $this->transport->init();

        $ok = $this->transport->execute($method, "$this->url$path", $body);
        if (!$ok) {
            throw new RequestException($this->transport->error());
        }

        $resp = new Response($this->transport->getStatus(), $this->transport->getBody());

        foreach ($this->transport->getHeaders() as $k => $v) {
            $resp->setHeader($k, $v);
        }

        $this->transport->close();

        return $resp;
    }
}
