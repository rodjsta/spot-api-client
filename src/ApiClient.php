<?php

namespace TravisAMiller\SpotTrackerApi;

use TravisAMiller\SpotTrackerApi\Request;
use TravisAMiller\SpotTrackerApi\Response;
use TravisAMiller\SpotTrackerApi\Transport;

class ApiClient
{
    /**
     * @var string
     */
    private $feedId = '';

    /**
     * @var string
     */
    private $password = '';

    /**
     * @var Transport\TransportInterface
     */
    private $transport;

    /**
     * ApiClient constructor.
     *
     * @param string $feedId
     * @param string $password
     * @param Transport\TransportInterface|null $transport
     */
    public function __construct(string $feedId, string $password = '', Transport\TransportInterface $transport = null)
    {
        $this->feedId = $feedId;
        $this->password = $password;
        $this->transport = $transport ?? $this->defaultTransport();
    }

    /**
     * Determine which HTTP transport to use.
     *
     * @return Transport\TransportInterface
     */
    private function defaultTransport(): Transport\TransportInterface
    {
        if (class_exists('\GuzzleHttp\Client')) {
            return new Transport\Guzzle6();
        }

        return new Transport\PhpStream();
    }

    /**
     * Send a generic request to the remote API server.
     *
     * @param Request\RequestInterface $request
     *
     * @return Response\ResponseInterface
     */
    public function send(Request\RequestInterface $request): Response\ResponseInterface
    {
        return $this->transport->send($request);
    }

    /**
     * Request the most recent location message from the feed.
     *
     * @return Response\LatestResponse
     */
    public function latest(): Response\LatestResponse
    {
        $request = new Request\LatestRequest(
            $this->feedId,
            $this->password
        );

        return $this->send($request);
    }

    /**
     * Request all available location messages from the feed.
     *
     * @param Request\Filter\MessagesFilterInterface|array $filter
     *
     * @return Response\MessagesResponse
     */
    public function messages($filter): Response\MessagesResponse
    {
        if (is_array($filter)) {
            $filter = new Request\Filter\MessagesFilter($filter);
        }

        $request = new Request\MessagesRequest(
            $this->feedId,
            $this->password,
            $filter
        );

        return $this->send($request);
    }
}
