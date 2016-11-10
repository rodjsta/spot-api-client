<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Request;

use TravisAMiller\SpotTrackerApi\Response\LatestResponse;
use TravisAMiller\SpotTrackerApi\Response\ResponseInterface;

/**
 * Latest Position Request
 *
 * Request the last known position from the Spot API. Always returns a single message.
 */
class LatestRequest extends AbstractRequest implements RequestInterface
{
    /**
     * {@inheritDoc}
     */
    protected $resource = 'latest';

    /**
     * {@inheritDoc}
     */
    public function getResponseObject(string $content, RequestInterface $request) : ResponseInterface
    {
        return new LatestResponse($content, $request);
    }
}
