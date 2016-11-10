<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Response;

use TravisAMiller\SpotTrackerApi\Spot\Message;

/**
 * Latest Feed Response
 *
 * Adds any desired functionality to the "latest" feed response object.
 */
class LatestResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * Return the most recent message.
     *
     * @return Message|null
     */
    public function getMessage()
    {
        $messages = $this->getMessages();

        return $messages[0];
    }
}
