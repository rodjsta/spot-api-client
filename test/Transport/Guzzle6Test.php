<?php

namespace TravisAMiller\SpotTrackerApiTest\Transport;

use GuzzleHttp;
use Psr\Http\Message\StreamInterface;
use TravisAMiller\SpotTrackerApi;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class Guzzle6Test extends TestCase
{
    /**
     * Transport object under test.
     *
     * @var SpotTrackerApi\Transport\PhpStream
     */
    protected $transport;

    /**
     * Mock Guzzle client.
     *
     * @var \PHPUnit_Framework_MockObject_MockObject|GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create test object for each test case.
     */
    public function setUp()
    {
        parent::setUp();
        $this->client = $this->createMock(GuzzleHttp\Client::class);
        $this->transport = new SpotTrackerApi\Transport\Guzzle6($this->client);
    }

    /**
     * Destroy test object after each test case.
     */
    public function tearDown()
    {
        $this->transport = null;
        $this->client = null;
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_can_intercept_exceptions()
    {
        $this->client->expects(
            $this->once()
        )->method(
            'send'
        )->willThrowException(new GuzzleHttp\Exception\RequestException(
            'test exception',
            new GuzzleHttp\Psr7\Request('GET', 'http://www.example.com')
        ));

        $request = $this->createMock(SpotTrackerApi\Request\RequestInterface::class);
        $request->expects($this->never())->method('getResponseObject');

        $this->expectException(SpotTrackerApi\Transport\TransportException::class);
        $this->transport->send($request);
    }

    /**
     * @test
     */
    public function it_can_send_requests_and_return_responses()
    {
        $content = '{"valid": "json"}';
        $request = $this->createMockRequestInterface($content);
        $response = $this->createMockGuzzleResponse($content);
        $this->client->expects($this->once())->method('send')->willReturn($response);

        $this->assertInstanceOf(
            SpotTrackerApi\Response\ResponseInterface::class,
            $this->transport->send($request)
        );
    }

    /**
     * @param string $content
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|GuzzleHttp\Psr7\Response
     */
    private function createMockGuzzleResponse($content)
    {
        // due to method chaining we need a response and stream mock
        $response = $this->createMock(GuzzleHttp\Psr7\Response::class);
        $stream = $this->createMock(StreamInterface::class);

        // response body returns a stream
        $response->method('getBody')->willReturn($stream);

        // stream returns the string contents
        $stream->method('getContents')->willReturn($content);

        return $response;
    }

    private function createMockRequestInterface($content)
    {
        $request = $this->createMock(SpotTrackerApi\Request\RequestInterface::class);

        // request provides the URL to fetch.
        $request->method('getUrl')->willReturn('http://www.example.com');

        // transport should then create the response object.
        $request->method('getResponseObject')->with(
            $this->equalTo($content),
            $this->isInstanceOf(SpotTrackerApi\Request\RequestInterface::class)
        )->willReturn(
            $this->createMock(SpotTrackerApi\Response\ResponseInterface::class)
        );

        return $request;
    }
}
