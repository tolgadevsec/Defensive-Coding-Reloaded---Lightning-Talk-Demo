<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Utils\Exporter\CSV\Exporter as CsvExporter;
use App\Utils\Exporter\CSV\DecoratedExporter as DecoratedCsvExporter;

class AppServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        $this->app->extend(CsvExporter::class, function ($service, $app) {
            return new DecoratedCsvExporter();
        });
    }
}