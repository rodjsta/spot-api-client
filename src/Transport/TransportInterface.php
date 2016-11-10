<?php declare(strict_types=1);

namespace TravisAMiller\SpotTrackerApi\Transport;

use TravisAMiller\SpotTrackerApi\Request\RequestInterface;
use TravisAMiller\SpotTrackerApi\Response\ResponseInterface;

/**
 * Transport Interface
 *
 * Contract allowing for multiple HTTP transport
 * implementations to interact with the library.
 */
interface TransportInterface
{
    /**
     * Sent HTTP request and wrap errors in a transport exception.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws TransportException
     */
    public function send(RequestInterface $request) : ResponseInterface;
}
