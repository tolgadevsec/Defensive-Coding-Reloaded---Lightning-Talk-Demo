<?php

namespace App\Listeners\Security;

use Illuminate\Events\Dispatcher;
use App\Events\Event;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Utils\String\Taint;

class SinkAccessSubscriber
{
    /**
     * @param object $event
     */
    public function handleSinkAccess($event) : void 
    {
        $sinkValue = null;

        switch(get_class($event))
        {
            case QueryExecuted::class: 
                $sinkValue = $event->sql; 
                break;
        }

        /**
         * @var Request $request
         */
        $request = app('request');
        $allInput = $request->all();

        $suspiciousInput = [];
  
        if($sinkValue !== null && $allInput !== null)
        {
            foreach($allInput as $inputKey => $inputValue)
            {
                $taintedSinkValueParts = Taint::Infer($inputValue, $sinkValue);
                if(!empty($taintedSinkValueParts))
                {
                    $suspiciousInput[$inputKey] = $taintedSinkValueParts;
                }
            }
        }

        // From https://laravel.io/articles/how-to-find-the-slowest-query-in-your-application
        $eventLocation = collect(debug_backtrace())->filter(function ($trace) {
            return !str_contains($trace['file'], 'vendor/');
        })->first();
        
        $eventClassParts = explode('\\', get_class($event));
        $eventClass = end($eventClassParts);

        echo view('sink', ['sinkValue' => $sinkValue, 
                           'suspiciousInput' => $suspiciousInput,
                           'eventLocation' => $eventLocation,
                           'eventClass' => $eventClass])->render();
    }

    /**
     * @return array<class-string, string> 
     */
    public function subscribe(Dispatcher $events) : array 
    {
        return [
            QueryExecuted::class => 'handleSinkAccess'
        ];
    }
}