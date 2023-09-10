<?php

namespace App\Utils\Exporter\CSV;

class Exporter 
{
    /**
     * @var array<array<int|string>> $data
     */
    private array $data;

    /**
     * @param array<array<int|string>> $data
     */
    public function setData(array $data) : void 
    {
        $this->data = $data;
    }

    public function export() : string 
    {
        $csvContent = '';
        foreach($this->data as $row)
        {
            $csvContent .= implode(',', (array)$row) . "\n";
        }
        return $csvContent;
    }
}