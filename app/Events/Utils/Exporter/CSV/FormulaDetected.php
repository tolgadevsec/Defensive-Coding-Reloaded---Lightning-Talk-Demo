<?php

namespace App\Events\Utils\Exporter\CSV;

use App\Events\Event;

class FormulaDetected extends Event
{
    public function __construct(int $rowIndex, int $columnIndex, string $detectedFormula) 
    {
        parent::__construct('In row ' . $rowIndex . ' and column ' . $columnIndex . ' - ' . $detectedFormula);
    }
}