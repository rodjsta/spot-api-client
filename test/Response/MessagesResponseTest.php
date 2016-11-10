<?php

namespace TravisAMiller\SpotTrackerApiTest\Response;

use TravisAMiller\SpotTrackerApi\Request\Filter\MessagesFilter;
use TravisAMiller\SpotTrackerApi\Request\MessagesRequest;
use TravisAMiller\SpotTrackerApi\Response\MessagesResponse;
use TravisAMiller\SpotTrackerApi\Spot\Feed;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class MessagesResponseTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_parse_valid_responses()
    {
        $response = $this->response('messages-successful-result.json');
        $this->assertSame(50, $response->getMessageCount(), 'Retrieve message count one.');
        $this->assertSame(217, $response->getTotalMessageCount(), 'Total message count is one.');
        $this->assertInstanceOf(Feed::class, $response->getFeed(), 'Feed object is accessible.');
        $this->assertCount(50, $response->getMessages(), 'Message feed contains expected messages');
    }

    /**
     * @test
     */
    public function it_can_determine_when_next_page_is_available()
    {
        $request = new MessagesRequest('LXij8mO5LIoE1Mg74X7pKx1JA8C5o385', '', new MessagesFilter());
        $response = new MessagesResponse($this->loadFixture('messages-page1-result.json'), $request);
        $this->assertTrue($response->hasNextPage());

        $request = $response->getNextPageRequest();
        $this->assertArraySubset(['start' => 50], $request->getFilter()->toArray());
        $response = new MessagesResponse($this->loadFixture('messages-page2-result.json'), $request);
        $this->assertTrue($response->hasNextPage());

        $request = $response->getNextPageRequest();
        $this->assertArraySubset(['start' => 100], $request->getFilter()->toArray());
        $response = new MessagesResponse($this->loadFixture('messages-page3-result.json'), $request);
        $this->assertTrue($response->hasNextPage());

        $request = $response->getNextPageRequest();
        $this->assertArraySubset(['start' => 150], $request->getFilter()->toArray());
        $response = new MessagesResponse($this->loadFixture('messages-page4-result.json'), $request);
        $this->assertTrue($response->hasNextPage());

        $request = $response->getNextPageRequest();
        $this->assertArraySubset(['start' => 200], $request->getFilter()->toArray());
        $response = new MessagesResponse($this->loadFixture('messages-page5-result.json'), $request);
        $this->assertFalse($response->hasNextPage());
    }

    /**
     * @test
     */
    public function it_can_determine_when_previous_page_is_available()
    {
        $request = new MessagesRequest('LXij8mO5LIoE1Mg74X7pKx1JA8C5o385', '', new MessagesFilter(['start' => 200]));
        $response = new MessagesResponse($this->loadFixture('messages-page5-result.json'), $request);
        $this->assertTrue($response->hasPreviousPage());

        $request = $response->getPreviousPageRequest();
        $this->assertArraySubset(['start' => 150], $request->getFilter()->toArray());
        $response = new MessagesResponse($this->loadFixture('messages-page4-result.json'), $request);
        $this->assertTrue($response->hasPreviousPage());

        $request = $response->getPreviousPageRequest();
        $this->assertArraySubset(['start' => 100], $request->getFilter()->toArray());
        $response = new MessagesResponse($this->loadFixture('messages-page3-result.json'), $request);
        $this->assertTrue($response->hasPreviousPage());

        $request = $response->getPreviousPageRequest();
        $this->assertArraySubset(['start' => 50], $request->getFilter()->toArray());
        $response = new MessagesResponse($this->loadFixture('messages-page3-result.json'), $request);
        $this->assertTrue($response->hasPreviousPage());

        $request = $response->getPreviousPageRequest();
        $this->assertArraySubset(['start' => 0], $request->getFilter()->toArray());
        $response = new MessagesResponse($this->loadFixture('messages-page3-result.json'), $request);
        $this->assertFalse($response->hasPreviousPage());
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
     * @return MessagesResponse
     */
    private function response(string $fixture)
    {
        return new MessagesResponse(
            $this->loadFixture($fixture),
            new MessagesRequest('FEED_ID_HERE')
        );
    }
}
