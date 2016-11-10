<?php

namespace TravisAMiller\SpotTrackerApiTest\Transport;

use phpmock\phpunit\PHPMock;
use TravisAMiller\SpotTrackerApi;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class PhpStreamTest extends TestCase
{
    /**
     * Allow easy mocking of PHP internal functions.
     */
    use PHPMock;

    /**
     * @var SpotTrackerApi\Transport\PhpStream
     */
    protected $transport;

    /**
     * Create test object for each test case.
     */
    public function setUp()
    {
        parent::setUp();
        $this->transport = new SpotTrackerApi\Transport\PhpStream;
    }

    /**
     * Destroy test object after each test case.
     */
    public function tearDown()
    {
        $this->transport = null;
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_can_open_http_sockets()
    {
        // Send HTTP request expecting 200 "PONG" response.
        $request = $this->createRequest('http://www.example.com', 'PONG');
        // open the sock to an HTTP server on port 80.
        $this->createFSockOpenMock('open-stream', 'tcp://www.example.com', 80);
        $this->createIsResourceMock();
        $this->createGetResourceTypeMock();
        // request should be written to server
        $this->createFWriteMock();
        // server response should be read
        $this->createStreamGetContentsMock();
        // open socket should be closed
        $this->createFCloseMock();

        $this->assertInstanceOf(
            SpotTrackerApi\Response\ResponseInterface::class,
            $this->transport->send($request),
            'Response was returned.'
        );
    }

    /**
     * @test
     */
    public function it_can_open_https_sockets()
    {
        // Send HTTP request expecting 200 "PONG" response.
        $request = $this->createRequest('https://www.example.com', 'PONG');
        // open the sock to an HTTPS server on port 443.
        $this->createFSockOpenMock('open-stream', 'ssl://www.example.com', 443);
        $this->createIsResourceMock();
        $this->createGetResourceTypeMock();
        // request should be written to server
        $this->createFWriteMock();
        // server response should be read
        $this->createStreamGetContentsMock();
        // open socket should be closed
        $this->createFCloseMock();

        $this->assertInstanceOf(
            SpotTrackerApi\Response\ResponseInterface::class,
            $this->transport->send($request),
            'Response was returned.'
        );
    }

    /**
     * @test
     */
    public function it_can_report_unknown_schemes()
    {
        $this->expectException(SpotTrackerApi\Transport\TransportException::class);

        $request = $this->createRequest('abc://www.example.com');

        $this->transport->send($request);
    }

    /**
     * @test
     */
    public function it_can_report_socket_open_failure()
    {
        $this->expectException(SpotTrackerApi\Transport\TransportException::class);

        // create request without expected response
        $request = $this->createRequest();
        // fail on fsockopen()
        $this->createFSockOpenMock(false);

        $this->transport->send($request);
    }

    /**
     * @test
     */
    public function it_can_report_failure_sending_request()
    {
        $this->expectException(SpotTrackerApi\Transport\TransportException::class);

        // create request without expected response
        $request = $this->createRequest();
        // socket should open successfully
        $this->createFSockOpenMock();
        $this->createIsResourceMock();
        $this->createGetResourceTypeMock();
        // write should fail
        $this->createFWriteMock(false);
        // open socket should be closed
        $this->createFCloseMock();

        $this->transport->send($request);
    }

    /**
     * @test
     */
    public function it_can_report_failed_responses()
    {
        $this->expectException(SpotTrackerApi\Transport\TransportException::class);

        // create request without expected response
        $request = $this->createRequest();
        // socket should open successfully
        $this->createFSockOpenMock();
        $this->createIsResourceMock();
        $this->createGetResourceTypeMock();
        // request should be written to server
        $this->createFWriteMock();
        // server response should fail
        $this->createStreamGetContentsMock(false);
        // open socket should be closed
        $this->createFCloseMock();

        $this->transport->send($request);
    }

    /**
     * @test
     */
    public function it_can_report_non_200_responses()
    {
        $this->expectException(SpotTrackerApi\Transport\TransportException::class);

        // create request without expected response
        $request = $this->createRequest();
        // socket should open successfully
        $this->createFSockOpenMock();
        $this->createIsResourceMock();
        $this->createGetResourceTypeMock();
        // request should be written to server
        $this->createFWriteMock();
        // response should be read from the server as a 404
        $this->createStreamGetContentsMock("HTTP/1.1 404 NOT FOUND");
        // open socket should be closed
        $this->createFCloseMock();

        $this->transport->send($request);
    }

    /**
     * Abstract creation of a request object.
     *
     * @param string $url
     * @param null $content
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|SpotTrackerApi\Request\RequestInterface
     */
    private function createRequest(string $url = "https://www.example.com", $content = null)
    {
        $request = $this->createMock(SpotTrackerApi\Request\RequestInterface::class);
        $request->method('getUrl')->willReturn($url);

        if ($content) {
            $request->expects(
                $this->once()
            )->method(
                'getResponseObject'
            )->with(
                $this->equalTo($content)
            )->willReturn(
                $this->createMock(SpotTrackerApi\Response\ResponseInterface::class)
            );
        } else {
            $request->expects(
                $this->never()
            )->method(
                'getResponseObject'
            );
        }

        return $request;
    }

    /**
     * Abstract the creation of a mock HTTP request.
     *
     * @param string $host
     * @param string $path
     * @param string $query
     *
     * @return string
     */
    private function createHttpRequest($host = "www.example.com", $path = "/", $query = "")
    {
        return "GET {$path}{$query} HTTP/1.0\r\n"
        . "Host: {$host}\r\n"
        . "Accept: application/json\r\n"
        . "\r\n";
    }

    /**
     * Abstract the creation of a mock fsockopen() function.
     *
     * @param string $return
     * @param string $param1
     * @param string $param2
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createFSockOpenMock($return = 'open-stream', $param1 = "ssl://www.example.com", $param2 = "443")
    {
        $function = $this->getFunctionMock('TravisAMiller\SpotTrackerApi\Transport', "fsockopen");

        $function->expects(
            $this->once()
        )->with(
            $this->equalTo($param1),
            $this->equalTo($param2)
        )->willReturn($return);

        return $function;
    }

    /**
     * Abstract the creation of a mock fwrite() function.
     *
     * @param bool $success
     * @param null $content
     * @param string $stream
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createFWriteMock($success = true, $content = null, $stream = 'open-stream')
    {
        // provide default content if not provided.
        $content = $content ?? $this->createHttpRequest();

        $function = $this->getFunctionMock('TravisAMiller\SpotTrackerApi\Transport', "fwrite");

        $function->expects(
            $this->once()
        )->with(
            $this->equalTo($stream),
            $this->equalTo($content)
        )->willReturn(
            $success === true ? strlen($content) : 0
        );

        return $function;
    }

    /**
     * Abstract the creation of a mock fclose() function.
     *
     * @param string $stream
     * @param bool $success
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createFCloseMock($stream = 'open-stream', $success = true)
    {
        $function = $this->getFunctionMock('TravisAMiller\SpotTrackerApi\Transport', "fclose");

        $function->expects(
            $this->once()
        )->with(
            $this->equalTo($stream)
        )->willReturn(
            $success
        );

        return $function;
    }

    /**
     * Abstract the creation of a is_resource() function.
     *
     * @param string $expected
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createIsResourceMock($expected = 'open-stream')
    {
        $function = $this->getFunctionMock('TravisAMiller\SpotTrackerApi\Transport', "is_resource");

        $function->expects(
            $this->any()
        )->willReturnCallback(function ($value) use ($expected) {
            return $value === $expected;
        });

        return $function;
    }

    /**
     * Abstract the creation of a mock get_resource_type() function.
     *
     * @param string $expected
     * @param string $type
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createGetResourceTypeMock($expected = 'open-stream', $type = 'stream')
    {
        $function = $this->getFunctionMock('TravisAMiller\SpotTrackerApi\Transport', "get_resource_type");

        $function->expects(
            $this->any()
        )->willReturnCallback(function ($value) use ($expected, $type) {
            return $value === $expected ? $type : 'Unknown';
        });

        return $function;
    }

    /**
     * Abstract the creation of a mock stream_get_contents() function.
     *
     * @param string $return
     * @param string $stream
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createStreamGetContentsMock($return = "HTTP/1.1 200 OK\r\n\r\nPONG", $stream = 'open-stream')
    {
        $function = $this->getFunctionMock('TravisAMiller\SpotTrackerApi\Transport', "stream_get_contents");

        $function->expects(
            $this->once()
        )->with(
            $this->equalTo($stream)
        )->willReturn(
            $return
        );

        return $function;
    }
}
