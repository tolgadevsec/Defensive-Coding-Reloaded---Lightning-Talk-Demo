<?php

use App\Listeners\Security\SuspiciousEventSubscriber;
use App\Events\Utils\Exporter\CSV\FormulaDetected;

class SuspiciousEventSubscriberTest extends TestCase
{
    public function testSubscribedToSuspiciousEvents() : void
    {
        $subscribedEvents = (new SuspiciousEventSubscriber())->subscribe();

        $this->assertArrayHasKey(FormulaDetected::class, $subscribedEvents);
    }
}