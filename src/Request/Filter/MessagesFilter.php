<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Request\Filter;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;

/**
 * MessagesFilter: Search the Message Feed
 *
 * Allow for filtering the positions returned from the messages API:
 *
 * start: allows pagination of the results in buckets of 50 (no other size is available)
 *        by setting a start record. The API Defaults to the first 50 records, you can
 *        then set a start record of 51, 101, 151,... to move back in the records.
 *
 * startDate: allows for excluding records older than a specific date. According
 *        to the API docs only UTC offset is allowed so any data provided will
 *        be converted to UTC before being sent. Note: the API only provides
 *        records back a max of 7 days regardless of this filter's value.
 *
 * endDate: allows for excluding records newer than a specific date. According to
 *        the API docs only UTC offset is allowed so any data provided will be
 *        converted to UTC. Note: the API only provides records back a max
 *        of 7 days regardless of this filter's value.
 */
class MessagesFilter extends AbstractFilter implements MessagesFilterInterface
{
    /**
     * Date format expected by the API.
     *
     * @var string
     */
    private static $dateFormat = DateTime::ATOM;

    /**
     * {@inheritDoc}
     */
    public function hasStartFilter(): bool
    {
        return isset($this->filters['start']);
    }

    /**
     * {@inheritDoc}
     */
    public function getStartFilter(): int
    {
        return $this->filters['start'];
    }

    /**
     * Set the starting record: (50 * n) + 1 where n >= 1
     *
     * @param int $start
     */
    protected function setStartFilter(int $start)
    {
        if ($start < 0) {
            throw new InvalidArgumentException('Start must be greater than or equal to zero.');
        }

        $this->filters['start'] = $start;
    }

    /**
     * {@inheritDoc}
     */
    public function hasStartDateFilter(): bool
    {
        return isset($this->filters['startDate']);
    }

    /**
     * {@inheritDoc}
     */
    public function getStartDateFilter(): DateTime
    {
        return DateTime::createFromFormat(self::$dateFormat, $this->filters['startDate']);
    }

    /**
     * Restrict oldest record.
     *
     * @param DateTime $startDate
     */
    protected function setStartDateFilter(DateTime $startDate)
    {
        $value = $this->toUTC($startDate);

        $this->verifyDateLimits('Start date', $value);

        $this->filters['startDate'] = $value->format(self::$dateFormat);
    }

    /**
     * {@inheritDoc}
     */
    public function hasEndDateFilter(): bool
    {
        return isset($this->filters['endDate']);
    }

    /**
     * {@inheritDoc}
     */
    public function getEndDateFilter(): DateTime
    {
        return DateTime::createFromFormat(self::$dateFormat, $this->filters['endDate']);
    }

    /**
     * Restrict newest record.
     *
     * @param DateTime $endDate
     */
    protected function setEndDateFilter(DateTime $endDate)
    {
        $value = $this->toUTC($endDate);

        $this->verifyDateLimits('End date', $value);

        $this->filters['endDate'] = $value->format(self::$dateFormat);
    }

    /**
     * Convert a given DateTime to UTC regardless of local timezone.
     *
     * @param DateTime $datetime
     *
     * @return DateTime
     */
    private function toUTC(DateTime $datetime): DateTime
    {
        $timezone = new DateTimeZone('UTC');

        return (clone $datetime)->setTimezone($timezone);
    }

    /**
     * All dates need to be within the last 7 days.
     *
     * @param DateTime $dateTime
     * @param string $filter
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    private function verifyDateLimits(string $filter, DateTime $dateTime)
    {
        $limit = new DateTime('-7 days midnight', $dateTime->getTimezone());

        if ($dateTime < $limit) {
            throw new InvalidArgumentException($filter . ' must be within the last 7 days.');
        }
    }
}
