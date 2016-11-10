<?php

namespace TravisAMiller\SpotTrackerApiTest;

use TravisAMiller\SpotTrackerApi;

class ApiClientTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_request_the_most_recent_position()
    {
        $transport = $this->createMock(SpotTrackerApi\Transport\TransportInterface::class);

        $transport
            ->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(SpotTrackerApi\Request\LatestRequest::class))
            ->willReturn($this->createMock(SpotTrackerApi\Response\LatestResponse::class));

        $this->assertInstanceOf(
            SpotTrackerApi\Response\LatestResponse::class,
            (new SpotTrackerApi\ApiClient('FEED_ID_HERE', '', $transport))->latest(),
            'Sends LatestRequest and returns LatestResponse'
        );
    }

    /**
     * @test
     */
    public function it_can_request_the_position_feed()
    {
        $transport = $this->createMock(SpotTrackerApi\Transport\TransportInterface::class);

        $transport
            ->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(SpotTrackerApi\Request\MessagesRequest::class))
            ->willReturn($this->createMock(SpotTrackerApi\Response\MessagesResponse::class));

        $this->assertInstanceOf(
            SpotTrackerApi\Response\MessagesResponse::class,
            (new SpotTrackerApi\ApiClient('FEED_ID_HERE', '', $transport))->messages(),
            'Sends MessagesRequest and returns MessagesResponse'
        );
    }

    /**
     * @test
     */
    public function it_can_send_generic_requests()
    {
        // mock a request to send
        $request = $this->createMock(SpotTrackerApi\Request\RequestInterface::class);
        $response = $this->createMock(SpotTrackerApi\Response\ResponseInterface::class);
        $transport = $this->createMock(SpotTrackerApi\Transport\TransportInterface::class);

        $transport
            ->expects($this->once())
            ->method('send')
            ->with($this->identicalTo($request))
            ->willReturn($response);

        $this->assertSame(
            $response,
            (new SpotTrackerApi\ApiClient('FEED_ID_HERE', '', $transport))->send($request),
            'Send generic request and returns generic response'
        );
    }
}
