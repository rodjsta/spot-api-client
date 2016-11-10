<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Request\Filter;

use InvalidArgumentException;

/**
 * AbstractFilter: Filter Object Utility Methods
 *
 * Utility methods to keep the filter objects as clean as possible
 * while still implementing the interface.
 */
class AbstractFilter implements FilterInterface
{
    /**
     * Filters to add to the request when sent to the server.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * {@inheritDoc}
     */
    public function __construct(array $filters = [])
    {
        $this->setProperties($filters);
    }


    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter($this->filters, function ($item) {
            return $item !== null;
        });
    }

    /**
     * Set known properties using filter key as name.
     *
     * @param array $filters
     */
    private function setProperties(array $filters)
    {
        foreach ($filters as $filter => $value) {
            $method = $this->getFilterMethod($filter);
            $this->$method($value);
        }
    }

    /**
     * Convert filter names into "study caps" format.
     *
     * @param $filter
     *
     * @return string
     */
    private function getFilterMethod($filter): string
    {
        // split into words (start_date => start date, start-date => state date)
        $method = str_replace(['-', '_'], ' ', $filter);
        // first letter of each word is capitalized: start date => Start Date
        $method = ucwords($method);
        // joins works back together: Start Date => StartDate
        $method = str_replace(' ', '', $method);
        // use a unique method signature: setStartDateFilter
        $method = 'set' . $method . 'Filter';

        // only allow the key/filter if a setting method exists
        if (method_exists($this, $method)) {
            return $method;
        }

        throw new InvalidArgumentException('Invalid filter name: ' . $filter);
    }
}
