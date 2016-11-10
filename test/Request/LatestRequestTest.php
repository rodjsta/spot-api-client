<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApiTest\Request;

use TravisAMiller\SpotTrackerApi\Request\LatestRequest;
use TravisAMiller\SpotTrackerApi\Response\LatestResponse;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class LatestRequestTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_generate_a_response_object()
    {
        $request = new LatestRequest('FEED_ID_HERE');

        $this->assertInstanceOf(
            LatestResponse::class,
            $request->getResponseObject('{"json": "object"}', $request),
            'Generates correct response object type.'
        );
    }

    /**
     * @test
     */
    public function it_can_generate_requests_for_public_feeds()
    {
        $request = new LatestRequest('FEED_ID_HERE');

        $this->assertSame(
            'https://api.findmespot.com/spot-main-web/consumer/rest-api/2.0/public/feed/FEED_ID_HERE/latest.json?',
            $request->getUrl(),
            'Generates URL without password parameter appended.'
        );
    }

    /**
     * @test
     */
    public function it_can_generate_requests_for_private_feeds()
    {
        $request = new LatestRequest('FEED_ID_HERE', 'FEED_PASSWORD_HERE');

        $this->assertSame(
            'https://api.findmespot.com/spot-main-web/consumer/rest-api/2.0/public/feed/FEED_ID_HERE/latest.json?feedPassword=FEED_PASSWORD_HERE',
            $request->getUrl(),
            'Generates URL with password parameter appended.'
        );
    }
}
