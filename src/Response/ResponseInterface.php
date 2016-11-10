<?php declare(strict_types=1);

namespace TravisAMiller\SpotTrackerApi\Response;

use TravisAMiller\SpotTrackerApi\Request\RequestInterface;
use TravisAMiller\SpotTrackerApi\Spot\Error;
use TravisAMiller\SpotTrackerApi\Spot\Feed;
use TravisAMiller\SpotTrackerApi\Spot\Message;

/**
 * Response Interface
 *
 * A contract all response objects should adhere to in order to work
 * in a common way in calling code.
 */
interface ResponseInterface
{
    /**
     * ResponseInterface constructor.
     *
     * @param string $content Raw JSON text.
     * @param RequestInterface $request Used to generate the response.
     */
    public function __construct(string $content, RequestInterface $request);

    /**
     * Check to see if the response has errors.
     *
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * Retrieve all errors returned by the server.
     *
     * @return Error[]
     */
    public function getErrors(): array;

    /**
     * Retrieve a specific error returned by the server.
     *
     * @param int $index
     *
     * @return Error
     */
    public function getError(int $index = 0): Error;

    /**
     * Get feed details.
     *
     * @return Feed
     */
    public function getFeed(): Feed;

    /**
     * Get the number of messages return in this request.
     *
     * Note: the API imposes an arbitrary limit of 50 messages per response.
     *
     * @return int
     */
    public function getMessageCount(): int;

    /**
     * Get to the total number of position messages available on the feed.
     *
     * This value will be greater than or equal to the message count.
     *
     * @return int
     */
    public function getTotalMessageCount(): int;

    /**
     * Get feed position messages.
     *
     * @return Message[]
     */
    public function getMessages(): array;

    /**
     * Get specific feed position messages.
     *
     * @param int $index
     *
     * @return Message
     */
    public function getMessage(int $index = 0): Message;
}
