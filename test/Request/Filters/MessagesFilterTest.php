<?php

namespace TravisAMiller\SpotTrackerApiTest\Request\Filters;

use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use TravisAMiller\SpotTrackerApi\Request\Filter\MessagesFilter;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class MessagesFilterTest extends TestCase
{
    use DefaultTestsTest;

    /**
     * @test
     */
    public function it_can_reject_unknown_filters()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid filter name: page');

        new MessagesFilter(['page' => 1]);
    }

    /**
     * @test
     */
    public function it_can_set_start_position_filter()
    {
        $filter = new MessagesFilter();
        $this->assertFalse($filter->hasStartFilter(), 'Start position filter should not be set');

        $filter = new MessagesFilter(['start' => 51]);
        $this->assertTrue($filter->hasStartFilter(), 'Start position filter should be set');
        $this->assertSame(51, $filter->getStartFilter(), 'Start position filter is set to expected value');
    }

    /**
     * @test
     */
    public function it_can_reject_invalid_start_positions()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Start must be greater than or equal to zero.');

        new MessagesFilter(['start' => -1]);
    }

    /**
     * @test
     */
    public function it_can_set_start_date_filter()
    {
        $startDate = new DateTime('today midnight', new DateTimeZone('America/Chicago'));

        $filter = new MessagesFilter();
        $this->assertFalse($filter->hasStartDateFilter(), 'Start date filter should not be set');

        $filter = new MessagesFilter(['startDate' => $startDate]);
        $this->assertTrue($filter->hasStartDateFilter(), 'Start date filter should be set');
        $this->assertEquals($this->dateToUTC($startDate), $filter->getStartDateFilter(), 'Start date was converted to UTC');
    }

    /**
     * @test
     */
    public function it_can_reject_start_dates_exceeding_seven_days()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Start date must be within the last 7 days.');

        new MessagesFilter(['startDate' => new \DateTime('2016-01-01')]);
    }

    /**
     * @test
     */
    public function it_can_set_end_date_filter()
    {
        $endDate = new DateTime('today midnight', new DateTimeZone('America/Chicago'));

        $filter = new MessagesFilter();
        $this->assertFalse($filter->hasEndDateFilter(), 'End date filter should not be set');

        $filter = new MessagesFilter(['endDate' => $endDate]);
        $this->assertTrue($filter->hasEndDateFilter(), 'End date filter should be set');
        $this->assertEquals($this->dateToUTC($endDate), $filter->getEndDateFilter(), 'End date was converted to UTC');
    }

    /**
     * @test
     */
    public function it_can_reject_end_dates_exceeding_seven_days()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('End date must be within the last 7 days.');

        new MessagesFilter(['endDate' => new \DateTime('2016-01-01')]);
    }
}
