<?php declare(strict_types=1);

namespace TravisAMiller\SpotTrackerApi\Request\Filter;

/**
 * FilterInterface: Filter Object Contract
 *
 * Allows for logic surrounding HTTP query variables to be isolated
 * into a single object per request type.
 *
 * @see NullFilter A filter for requests not allow parameters.
 * @see MessagesFilter A filter for the /messages endpoint.
 */
interface FilterInterface
{
    /**
     * Inject filter values as array for convenience.
     *
     * @param array $filters
     */
    public function __construct(array $filters = []);

    /**
     * Extract mutated values as associative array for use in HTTP request.
     *
     * @return array
     */
    public function toArray(): array;
}
