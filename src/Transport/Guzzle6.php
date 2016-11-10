<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Transport;

use GuzzleHttp;
use TravisAMiller\SpotTrackerApi\Request\RequestInterface;
use TravisAMiller\SpotTrackerApi\Response\ResponseInterface;

/**
 * Guzzle 6 HTTP Transport
 *
 * This class wraps the Guzzle 6 library and uses it to make requests to
 * the remote server. It is the preferred HTTP transport if you can use
 * "guzzlehttp/guzzle" in your project. Otherwise, you are will be
 * automatically downgraded to the built-in PHPStream transport.
 */
class Guzzle6 implements TransportInterface
{
    /**
     * Guzzle client used to send the HTTP requests.
     *
     * @var GuzzleHttp\Client
     */
    private $client = null;

    /**
     * Guzzle6 constructor.
     *
     * @param GuzzleHttp\Client|null $client
     */
    public function __construct(GuzzleHttp\Client $client = null)
    {
        $this->client = $client ?? new GuzzleHttp\Client();
    }

    /**
     * {@inheritDoc}
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        $httpRequest = null;
        $httpResponse = null;
        $content = null;

        try {
            $httpRequest = new GuzzleHttp\Psr7\Request('GET', $request->getUrl());
            $httpResponse = $this->client->send($httpRequest);
            $content = $httpResponse->getBody()->getContents();
        } catch (\Exception $exception) {
            throw new TransportException(
                'Request to remote API failed.',
                $exception->getCode(),
                $httpRequest,
                $httpResponse,
                $exception
            );
        }

        return $request->getResponseObject($content, $request);
    }
}
