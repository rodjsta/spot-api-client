<?php

namespace TravisAMiller\SpotTrackerApiTest\Response;

use TravisAMiller\SpotTrackerApi\Request\LatestRequest;
use TravisAMiller\SpotTrackerApi\Response\LatestResponse;
use TravisAMiller\SpotTrackerApi\Spot\Feed;
use TravisAMiller\SpotTrackerApi\Spot\Message;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class LatestResponseTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_parse_valid_responses()
    {
        $response = $this->response('latest-successful-result.json');
        $this->assertSame(1, $response->getMessageCount(), 'Retrieve message count one.');
        $this->assertSame(1, $response->getTotalMessageCount(), 'Total message count is one.');
        $this->assertInstanceOf(Feed::class, $response->getFeed(), 'Feed object is accessible.');
        $this->assertCount(1, $response->getMessages(), 'Message feed contains a single message');
    }

    /**
     * @test
     */
    public function it_can_return_single_message_directly()
    {
        $response = $this->response('latest-successful-result.json');

        $message = $response->getMessage();

        $this->assertInstanceOf(Message::class, $message);
        $this->assertSame(651341874, $message->getId());
    }

    /**
     * Load json data from fixture.
     *
     * @param string $fixture
     *
     * @return string
     */
    private function loadFixture(string $fixture)
    {
        return file_get_contents(__DIR__ . '/fixtures/' . $fixture);
    }

    /**
     * Create a response object feeding it known good JSON.
     *
     * @param string $fixture
     *
     * @return LatestResponse
     */
    private function response(string $fixture)
    {
        return new LatestResponse(
            $this->loadFixture($fixture),
            new LatestRequest('FEED_ID_HERE')
        );
    }
}
