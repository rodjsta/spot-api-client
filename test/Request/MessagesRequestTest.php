<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApiTest\Request;

use TravisAMiller\SpotTrackerApi\Request\Filter\MessagesFilter;
use TravisAMiller\SpotTrackerApi\Request\MessagesRequest;
use TravisAMiller\SpotTrackerApi\Response\MessagesResponse;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class MessagesRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_generate_the_appropriate_response_object()
    {
        $request = new MessagesRequest('FEED_ID_HERE');

        $this->assertInstanceOf(
            MessagesResponse::class,
            $request->getResponseObject('{"json": "object"}', $request),
            'Generates correct response object type.'
        );
    }

    /**
     * @test
     */
    public function it_can_generate_requests_for_public_feeds()
    {
        $request = new MessagesRequest('FEED_ID_HERE');

        $this->assertSame(
            'https://api.findmespot.com/spot-main-web/consumer/rest-api/2.0/public/feed/FEED_ID_HERE/message.json?',
            $request->getUrl(),
            'Generates URL without password parameter appended.'
        );
    }

    /**
     * @test
     */
    public function it_can_generate_requests_for_private_feeds()
    {
        $request = new MessagesRequest('FEED_ID_HERE', 'FEED_PASSWORD_HERE');

        $this->assertSame(
            'https://api.findmespot.com/spot-main-web/consumer/rest-api/2.0/public/feed/FEED_ID_HERE/message.json?feedPassword=FEED_PASSWORD_HERE',
            $request->getUrl(),
            'Generates URL with password parameter appended.'
        );
    }

    /**
     * @test
     */
    public function it_can_generate_requests_with_filters()
    {
        $startDate = new \DateTime("yesterday");
        $endDate = new \DateTime("today");

        $request = new MessagesRequest('FEED_ID_HERE', 'PASSWORD_HERE', new MessagesFilter([
            'start' => 51,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]));

        $url = $request->getUrl();

        $this->assertContains("feedPassword=PASSWORD_HERE", $url, 'Generates URL with start');
        $this->assertContains("start=51", $url, 'Generates URL with start');
        $this->assertContains("startDate={$this->dateToUrlFormat($startDate)}", $url, 'Generates URL with start date');
        $this->assertContains("endDate={$this->dateToUrlFormat($endDate)}", $url, 'Generates URL with end date');
    }
}
