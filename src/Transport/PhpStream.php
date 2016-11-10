<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Transport;

use TravisAMiller\SpotTrackerApi\Request\RequestInterface;
use TravisAMiller\SpotTrackerApi\Response\ResponseInterface;

/**
 * PHP Stream HTTP Transport Wrapper
 *
 * An extremely simplistic HTTP transport using PHP streams. Although this
 * transport is the default, I advise those using this client library to
 * install "guzzlehttp/guzzle" version 6 instead of using this driver.
 */
class PhpStream implements TransportInterface
{
    /**
     * {@inheritDoc}
     */
    public function send(RequestInterface $request) : ResponseInterface
    {
        $httpSocket = null;
        $httpRequest = $this->buildHttpRequest($request->getUrl());
        $httpResponse = '';
        $content = '';

        try {
            $httpSocket = $this->openSocket($request->getUrl());
            $httpResponse = $this->sendHttpRequest($httpSocket, $httpRequest);
            $content = $this->parseHttpResponse($httpResponse);
        } catch (\Exception $exception) {
            throw new TransportException(
                'Request to remote API failed.',
                $exception->getCode(),
                $httpRequest,
                $httpResponse,
                $exception
            );
        } finally {
            $this->closeSocket($httpSocket);
        }

        return $request->getResponseObject($content, $request);
    }

    /**
     * Open socket connection to the remote server.
     *
     * @param string $url
     *
     * @return resource
     */
    private function openSocket(string $url)
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $host = parse_url($url, PHP_URL_HOST);

        if ($scheme === 'https') {
            $host = 'ssl://' . $host;
            $port = 443;
        } elseif ($scheme === 'http') {
            $host = 'tcp://' . $host;
            $port = 80;
        } else {
            throw new \RuntimeException('URL contained invalid resource scheme:' . $scheme);
        }

        $socket = fsockopen($host, $port);

        if (!is_resource($socket)) {
            throw new \RuntimeException('failed to create socket');
        }

        return $socket;
    }

    /**
     * Close open socket to remote API server.
     *
     * @param resource $socket
     */
    private function closeSocket($socket)
    {
        if (is_resource($socket) &&
            get_resource_type($socket) === 'stream'
        ) {
            fclose($socket);
        }
    }

    /**
     * Set the HTTP request to the remove server and capture the response.
     *
     * @param resource $socket
     * @param string $request
     *
     * @return string
     */
    private function sendHttpRequest($socket, string $request) : string
    {
        // verify entire request is sent.
        $sent = fwrite($socket, $request);
        if ($sent !== strlen($request)) {
            throw new \RuntimeException('failed to write request');
        }

        // verify an response is sent.
        $response = stream_get_contents($socket);
        if (!is_string($response)) {
            throw new \RuntimeException('failed to read response');
        }

        return $response;
    }

    /**
     * Build the raw HTTP request.
     *
     * @param string $url
     *
     * @return string
     */
    private function buildHttpRequest(string $url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        $query = parse_url($url, PHP_URL_QUERY);
        $host = parse_url($url, PHP_URL_HOST);

        if (strlen((string)$path) === 0) {
            $path = '/';
        }

        if (strlen((string)$query) > 0) {
            $query = '?' . $query;
        }

        return "GET {$path}{$query} HTTP/1.0\r\n"
        . "Host: {$host}\r\n"
        . "Accept: application/json\r\n"
        . "\r\n";
    }

    /**
     * Parse the raw HTTP response into headers and content.
     *
     * @param string $response
     *
     * @return string
     */
    private function parseHttpResponse(string $response): string
    {
        $response = explode("\r\n\r\n", $response, 2);
        $this->validateHttpResponse(explode("\r\n", $response[0]));
        return $response[1] ?? '';
    }

    /**
     * Validates the HTTP status code sent by remote server.
     *
     * @param array $headers
     *
     * @throws \RuntimeException
     */
    private function validateHttpResponse(array $headers)
    {
        $header = preg_grep('#^HTTP/#', $headers);
        if (count($header) !== 1) {
            throw new \RuntimeException('Invalid HTTP response');
        }

        $statusParts = explode(' ', $header[0], 3);
        $statusCode = $statusParts[1] ?? '';
        $statusDescription = $statusParts[2] ?? '';
        if (!is_numeric($statusCode)) {
            throw new \RuntimeException('Invalid HTTP response');
        }
        if ($statusCode !== '200') {
            throw new \RuntimeException($statusDescription, (int)$statusCode);
        }
    }
}
