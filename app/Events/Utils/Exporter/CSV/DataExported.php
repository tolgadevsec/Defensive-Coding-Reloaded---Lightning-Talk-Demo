<?php

namespace App\Events\Utils\Exporter\CSV;

use App\Events\Event;

class DataExported extends Event
{
    public string $csv;

    public function __construct(string $csv)
    {
        $this->csv = $csv;
    }
}