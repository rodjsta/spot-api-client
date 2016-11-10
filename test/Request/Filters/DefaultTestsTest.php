<?php

namespace TravisAMiller\SpotTrackerApiTest\Request\Filters;

use TravisAMiller\SpotTrackerApi\Request\Filter\NullFilter;
use TravisAMiller\SpotTrackerApiTest\TestCase;

/**
 * Class DefaultTestsTest
 *
 * @mixin TestCase
 */
trait DefaultTestsTest
{
    /**
     * @test
     */
    public function it_can_be_instantiated_with_no_filters()
    {
        $filter = new NullFilter();
        $this->assertCount(0, $filter->toArray(), 'Should not contain any filters.');
    }

    /**
     * @test
     */
    public function it_can_be_instantiated_with_empty_filters()
    {
        $filter = new NullFilter([]);
        $this->assertCount(0, $filter->toArray(), 'Should not contain any filters.');
    }
}
