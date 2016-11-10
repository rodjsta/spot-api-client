<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Request\Filter;

/**
 * NullFilter: A Parameter-less Filter
 *
 * Some requests (like /latest) do not support any query parameters. Rather
 * them complicating the request handling code with checks specific types
 * and skipping the filter this object will guarantee no parameters.
 */
class NullFilter implements FilterInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(array $filters = [])
    {
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [];
    }
}
