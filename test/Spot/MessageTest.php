<?php

namespace TravisAMiller\SpotTrackerApiTest\Spot;

use TravisAMiller\SpotTrackerApi\Spot\Message;
use TravisAMiller\SpotTrackerApi\Spot\Messenger;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class MessageTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated_form_json_object()
    {
        $json = json_decode(file_get_contents(__DIR__ . '/fixtures/message.json'), false);

        $message = Message::fromJsonObject($json);

        $this->assertSame(651341874, $message->getId(), 'ID is accessible.');
        $this->assertSame(1478330156, $message->getTimestamp(), 'Timestamp is accessible.');
        $this->assertSame("TRACK", $message->getType(), 'Message type is accessible.');
        $this->assertSame(29.54802, $message->getLatitude(), 'Latitude is accessible.');
        $this->assertSame(-95.04385, $message->getLongitude(), 'Longitude is accessible.');
        $this->assertInstanceOf(Messenger::class, $message->getMessenger(), 'Messenger object is accessible.');
    }
}
