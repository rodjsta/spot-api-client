<?php

namespace TravisAMiller\SpotTrackerApiTest\Transport;

use TravisAMiller\SpotTrackerApi\Transport\TransportException;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class TransportExceptionTest extends TestCase
{
    /**
     * Mock HTTP request.
     *
     * @var string
     */
    public static $request =
        "GET /index.php?var=value HTTP/1.0\r\n" .
        "Host: www.example.com\r\n" .
        "Accept: text/html\r\n" .
        "\r\n";

    /**
     * Mock HTTP response.
     *
     * @var string
     */
    public static $response =
        "HTTP/1.0 404 NOT FOUND\r\n" .
        "Content-Type: text/html\r\n" .
        "\r\n" .
        "<html><head><title>Error 404</title></head><body><h1>Not Found</h1></body></html>";

    /**
     * @test
     */
    public function it_can_store_http_request_and_response_text()
    {
        $exception = new TransportException('API request failed.', 404, self::$request, self::$response);

        $this->assertSame('API request failed.', $exception->getMessage(), 'Message is available');
        $this->assertSame(404, $exception->getCode(), 'Code is available');
        $this->assertSame(self::$request, $exception->getRequest(), 'Request is available');
        $this->assertSame(self::$response, $exception->getResponse(), 'Response is available');
    }

    /**
     * @test
     */
    public function it_can_store_http_request_and_response_object()
    {
        $request = new class()
        {
            public function __toString()
            {
                return TransportExceptionTest::$request;
            }
        };

        $response = new class()
        {
            public function __toString()
            {
                return TransportExceptionTest::$response;
            }
        };

        $exception = new TransportException('API request failed.', 404, $request, $response);

        $this->assertSame('API request failed.', $exception->getMessage(), 'Message is available');
        $this->assertSame(404, $exception->getCode(), 'Code is available');
        $this->assertSame(self::$request, $exception->getRequest(), 'Request is available');
        $this->assertSame(self::$response, $exception->getResponse(), 'Response is available');
    }

    /**
     * @test
     */
    public function it_can_notify_when_no_http_request_or_response_available()
    {
        $exception = new TransportException('API request failed.', 404);

        $this->assertSame('API request failed.', $exception->getMessage(), 'Message is available');
        $this->assertSame(404, $exception->getCode(), 'Code is available');
        $this->assertSame('no data was provided', $exception->getRequest(), 'Request is not available');
        $this->assertSame('no data was provided', $exception->getResponse(), 'Response is not available');
    }

    /**
     * @test
     */
    public function it_can_handle_request_or_response_objects_that_cannot_be_cast_to_string()
    {
        $exception = new TransportException('API request failed.', 404, new \stdClass(), new \stdClass());

        $this->assertSame('API request failed.', $exception->getMessage(), 'Message is available');
        $this->assertSame(404, $exception->getCode(), 'Code is available');
        $this->assertSame('stdClass', $exception->getRequest(), 'Request class is available');
        $this->assertSame('stdClass', $exception->getResponse(), 'Response class is available');
    }
}
