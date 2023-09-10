<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;

    private string $message;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }

    public function getMessage() : string 
    {
        return $this->message;
    }
}