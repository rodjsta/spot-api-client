<?php

namespace TravisAMiller\SpotTrackerApiTest\Spot;

use TravisAMiller\SpotTrackerApi\Spot\Feed;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class FeedTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated_form_json_object()
    {
        $json = json_decode(file_get_contents(__DIR__ . '/fixtures/feed.json'), false);

        $feed = Feed::fromJsonObject($json);

        $this->assertSame("LXij8mO5LIoE1Mg74X7pKx1JA8C5o385", $feed->getId(), 'ID is accessible.');
        $this->assertSame("My Spot Feed", $feed->getName(), 'Name is accessible.');
        $this->assertSame("My 2016 Sailing Vacation", $feed->getDescription(), 'Description is accessible.');
        $this->assertSame(7, $feed->getDays(), 'Day range is accessible.');
    }
}
