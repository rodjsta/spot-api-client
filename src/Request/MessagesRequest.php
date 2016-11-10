<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Request;

use TravisAMiller\SpotTrackerApi\Response\MessagesResponse;
use TravisAMiller\SpotTrackerApi\Response\ResponseInterface;

/**
 * Messages Feed Request
 *
 * Requests all available positions from the Spot API. May return one or
 * more position messages.
 *
 * Note: the results to this request may be filtered with a filter
 * that implements the MessagesFilterInterface.
 */
class MessagesRequest extends AbstractRequest implements RequestInterface
{
    /**
     * {@inheritDoc}
     */
    protected $resource = 'message';

    /**
     * Messages Request constructor.
     *
     * Override the base constructor require all filters passed into
     * this request comply with the MessagesFilterInterface interface.
     *
     * @param string $feedId
     * @param string $password
     * @param Filter\MessagesFilterInterface|null $filter
     */
    public function __construct(string $feedId, string $password = '', Filter\MessagesFilterInterface $filter = null)
    {
        parent::__construct($feedId, $password, $filter ?? new Filter\MessagesFilter());
    }

    /**
     * {@inheritDoc}
     */
    public function getResponseObject(string $content, RequestInterface $request) : ResponseInterface
    {
        return new MessagesResponse($content, $request, new Filter\MessagesFilter());
    }
}
