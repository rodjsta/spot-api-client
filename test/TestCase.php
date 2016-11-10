<?php

namespace TravisAMiller\SpotTrackerApiTest;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Convert a datetime to the UTC time zone.
     *
     * @param \DateTime $dateTime
     *
     * @return \DateTime
     */
    protected function dateToUTC(\DateTime $dateTime)
    {
        return (clone $dateTime)->setTimezone(new \DateTimeZone('UTC'));
    }

    /**
     * Since the code contains data range checks we have to manipulate the
     * date into the expected format to match the query parameters.
     *
     * @param \DateTime $dateTime
     *
     * @return string
     */
    protected function dateToUrlFormat(\DateTime $dateTime)
    {
        return urlencode(
            $this->dateToUTC($dateTime)->format(\DateTime::ATOM)
        );
    }
}
