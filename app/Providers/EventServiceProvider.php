<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\Security\SuspiciousEventSubscriber;
use App\Listeners\Security\SinkAccessSubscriber;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string> $subscribe
     */
    protected $subscribe = [
        SuspiciousEventSubscriber::class,
        SinkAccessSubscriber::class
    ];
}