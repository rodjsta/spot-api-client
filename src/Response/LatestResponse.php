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
     * @param int $index is not used by this method but is require for interface compliance.
     *
     * @return Message|null
     */
    public function getMessage(int $index = 0): Message
    {
        $messages = $this->getMessages();

        return count($messages) > 0 ? $messages[0] : null;
    }
}
