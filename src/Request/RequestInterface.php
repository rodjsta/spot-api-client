<?php declare(strict_types=1);

namespace TravisAMiller\SpotTrackerApi\Request;

use TravisAMiller\SpotTrackerApi\Request\Filter\FilterInterface;
use TravisAMiller\SpotTrackerApi\Response\ResponseInterface;

/**
 * Request Interface
 *
 * A contract all request objects should adhere to in order to work
 * property with the HTTP transport.
 */
interface RequestInterface
{
    /**
     * Access the feed ID.
     *
     * @return string
     */
    public function getFeedId(): string;

    /**
     * Access the feed password.
     *
     * @return string
     */
    public function getFeedPassword(): string;

    /**
     * Access the request filters.
     *
     * @return FilterInterface
     */
    public function getFilter(): FilterInterface;

    /**
     * Build request URL in expected format.
     *
     * @return string
     */
    public function getUrl() : string;

    /**
     * Build the expected response object.
     *
     * @param string $content
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function getResponseObject(string $content, RequestInterface $request) : ResponseInterface;
}
