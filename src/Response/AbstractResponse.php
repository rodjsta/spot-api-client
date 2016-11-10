<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Response;

use TravisAMiller\SpotTrackerApi\Request\RequestInterface;
use TravisAMiller\SpotTrackerApi\Spot\Error;
use TravisAMiller\SpotTrackerApi\Spot\Feed;
use TravisAMiller\SpotTrackerApi\Spot\Message;

/**
 * Abstract Response
 *
 * Provides properties and generic functionality all responses depend on.
 */
class AbstractResponse implements ResponseInterface
{
    /**
     * The parsed JSON returned from the HTTP request.
     *
     * @var mixed
     */
    protected $json;

    /**
     * Message object cache so they are only parsed once.
     *
     * @var Message[]|null
     */
    protected $messages = null;

    /**
     * Error object cache so they are only parsed once.
     *
     * @var Error[]|null
     */
    protected $errors = null;

    /**
     * The request used to generate this response.
     *
     * @var RequestInterface
     * @var RequestInterface
     */
    protected $request = null;

    /**
     * {@inheritDoc}
     */
    public function __construct(string $content, RequestInterface $request)
    {
        $this->json = json_decode($content);
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     */
    public function hasErrors(): bool
    {
        return isset($this->json->response->errors);
    }

    /**
     * {@inheritDoc}
     */
    public function getErrors(): array
    {
        if ($this->errors === null) {
            $errors = $this->json->response->errors->error;

            if (!is_array($errors)) {
                $errors = [$errors];
            }

            $this->errors = array_map(function ($error) {
                return Error::fromJsonObject($error);
            }, $errors);
        }

        return $this->errors;
    }

    /**
     * {@inheritDoc}
     */
    public function getError(int $index = 0): Error
    {
        return $this->getErrors()[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function getFeed(): Feed
    {
        return Feed::fromJsonObject($this->json->response->feedMessageResponse->feed);
    }

    /**
     * {@inheritDoc}
     */
    public function getMessageCount(): int
    {
        return (int)$this->json->response->feedMessageResponse->count;
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalMessageCount(): int
    {
        return (int)$this->json->response->feedMessageResponse->totalCount;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessages(): array
    {
        if ($this->messages === null) {
            // API returns array for multiple results and single object if one result.
            $messages = $this->json->response->feedMessageResponse->messages->message;
            if (!is_array($messages)) {
                $messages = [$messages];
            }
            // map each stdClass to a Message object.
            $this->messages = array_map(function ($item) {
                return Message::fromJsonObject($item);
            }, $messages);
        }

        return $this->messages;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(int $index = 0): Message
    {
        return $this->getMessage()[$index];
    }
}
