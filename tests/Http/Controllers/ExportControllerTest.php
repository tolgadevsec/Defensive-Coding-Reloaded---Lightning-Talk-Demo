<?php

use App\Http\Controllers\ExportController;
use App\Utils\Exporter\CSV\Exporter as CsvExporter;
use App\Utils\Exporter\CSV\DecoratedExporter as DecoratedCsvExporter;
use App\Events\Utils\Exporter\CSV\FormulaDetected;
use Illuminate\Support\Facades\Event;

class ExportControllerTest extends TestCase
{   
    public function testCsvMethodDoesNotTriggerFormulaDetectedEvent() : void
    {
        Event::fake();
        
        $controller = new ExportController();
        $exporter = new CsvExporter();
        $controller->csv($exporter);

        Event::assertNotDispatched(FormulaDetected::class);
    }

    public function testCsvMethodTriggersFormulaDetectedEvent() : void 
    {
        Event::fake();
        
        $controller = new ExportController();
        $exporter = new DecoratedCsvExporter();
        $controller->csv($exporter);

        Event::assertDispatched(FormulaDetected::class);
    }
}