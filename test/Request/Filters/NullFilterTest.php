<?php

namespace TravisAMiller\SpotTrackerApiTest\Request\Filters;

use TravisAMiller\SpotTrackerApi\Request\Filter\NullFilter;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class NullFilterTest extends TestCase
{
    use DefaultTestsTest;

    /**
     * @test
     */
    public function it_can_ignore_constructor_args()
    {
        $filter = new NullFilter(['test' => 'value']);

        $this->assertCount(0, $filter->toArray(), 'Should not contain any filters.');
    }
}
