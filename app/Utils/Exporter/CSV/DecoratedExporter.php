<?php

namespace App\Utils\Exporter\CSV;

use App\Events\Utils\Exporter\CSV\DataSet;
use App\Events\Utils\Exporter\CSV\FormulaDetected;

class DecoratedExporter extends Exporter
{
    /**
     * @param array<array<int|string>> $data
     */
    public function setData(array $data) : void 
    {
        $this->formulaCheck($data);
        parent::setData($data);
        event(new DataSet());
    }

    /**
     * This checks only for "=" in the demo, there are further characters
     * to consider - See https://owasp.org/www-community/attacks/CSV_Injection
     *
     * @param array<array<int|string>> $data
     */
    private function formulaCheck(array $data) : void 
    {
        $i = 0;
        foreach($data as $row)
        {
            $j = 1;
            foreach($row as $cell)
            {
                if(is_string($cell) && str_starts_with($cell, '='))
                {
                    event(new FormulaDetected($i,$j,$cell));
                }

                $j++;
            }

            $i++;
        }
    }
}