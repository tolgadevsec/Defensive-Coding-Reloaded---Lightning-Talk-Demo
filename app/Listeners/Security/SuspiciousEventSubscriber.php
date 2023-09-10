<?php

namespace App\Listeners\Security;

use Illuminate\Events\Dispatcher;
use App\Events\Event;
use App\Events\Utils\Exporter\CSV\FormulaDetected;

class SuspiciousEventSubscriber
{
    public function handleSuspiciousEvent(Event $event) : void 
    {
        $eventClassParts = explode('\\', get_class($event));
        $eventClass = end($eventClassParts);
        echo view('event', ['eventClass' => $eventClass, 'eventMessage' => $event->getMessage()])->render();
    }

    /**
     * @return array<class-string, string> 
     */
    public function subscribe() : array 
    {
        return [
            FormulaDetected::class => 'handleSuspiciousEvent'
        ];
    }
}