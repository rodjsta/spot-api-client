<?php

namespace TravisAMiller\SpotTrackerApiTest\Spot;

use TravisAMiller\SpotTrackerApi\Spot\Messenger;
use TravisAMiller\SpotTrackerApiTest\TestCase;

class MessengerTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_instantiated_form_json_object()
    {
        $messenger = new Messenger("0-8222286", "TYSC 42", "SPOT 2");

        $this->assertSame("0-8222286", $messenger->getId(), 'ID is accessible.');
        $this->assertSame("TYSC 42", $messenger->getName(), 'Name is accessible.');
        $this->assertSame("SPOT 2", $messenger->getModel(), 'Model is accessible.');
    }
}
