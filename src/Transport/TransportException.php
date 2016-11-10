<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Transport;

use Exception;

/**
 * HTTP Transport Exception Wrapper
 *
 * Each different HTTP transport (curl, guzzle, etc) is will likely have it's own
 * error handling system. This object represents a generic HTTP exception that
 * can wrap calls for each transport and alleviate calling code from having
 * to worry about implementing transport-specific error/exception logic.
 */
class TransportException extends Exception
{
    /**
     * Raw text of the HTTP request send to the API endpoint.
     *
     * @var string
     */
    private $request;

    /**
     * Raw text of the HTTP response received from the API endpoint.
     *
     * @var string
     */
    private $response;

    /**
     * TransportException constructor.
     *
     * @param string $message
     * @param int $code
     * @param string $request
     * @param string $response
     * @param Exception $previous
     */
    public function __construct(
        string $message,
        int $code,
        $request = null,
        $response = null,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->request = $this->castToString($request);
        $this->response = $this->castToString($response);
    }

    /**
     * Allow request/response to be passed as either an object or a string.
     *
     * @param mixed $value
     *
     * @return string
     */
    private function castToString($value)
    {
        if (is_object($value) && !method_exists($value, '__toString')) {
            $value = get_class($value);
        } else {
            $value = (string)$value;
        }

        if (strlen($value) > 0) {
            return $value;
        }

        return 'no data was provided';
    }

    /**
     * Returns the raw HTTP request text.
     *
     * @return string
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * Returns the raw HTTP response text.
     *
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }
}
