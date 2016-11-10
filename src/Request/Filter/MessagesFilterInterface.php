<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Request\Filter;

use DateTime;

/**
 * Messages Filter Interface
 *
 * Users of the library may wish to write their own custom message filters.
 * An example of this might be a "yesterday" filter that always returns
 * results from the previous day.
 *
 * This contract enforces the expected methods of a filter on the messages endpoint.
 */
interface MessagesFilterInterface extends FilterInterface
{
    /**
     * Indicate if a start position was specified in the filter.
     *
     * @return bool
     */
    public function hasStartFilter(): bool;

    /**
     * Get the starting message position.
     *
     * @return int
     */
    public function getStartFilter(): int;

    /**
     * Indicate if a start date was specified in the filter.
     *
     * @return bool
     */
    public function hasStartDateFilter(): bool;

    /**
     * Get the starting date value.
     *
     * @return Datetime
     */
    public function getStartDateFilter(): DateTime;

    /**
     * Indicate if an end date was specified in the filter.
     *
     * @return bool
     */
    public function hasEndDateFilter(): bool;

    /**
     * Get the ending date value.
     *
     * @return Datetime|null
     */
    public function getEndDateFilter(): DateTime;
}
