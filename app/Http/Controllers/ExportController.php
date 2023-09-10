<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Utils\Exporter\CSV\Exporter as CsvExporter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ExportController extends BaseController
{
    public function csv(CsvExporter $csvExporter) : string 
    {
        $titleColumns = [ 'ID', 'Title', 'Description', 'Rating' ];
        $monthlyReviews = DB::table('monthly_reviews')->get()->toArray();
        
        array_unshift($monthlyReviews, $titleColumns);

        $csvExporter->setData($monthlyReviews);
        
        $csvContent = $csvExporter->export();

        return '<pre>' . $csvContent . '</pre>';
    }

    public function json(Request $request) : string 
    {
        $title = $request->input('title');
        $query = 'select * from monthly_reviews';
        
        if(!empty($title))
        {
            $query .= ' where title LIKE "' . $title . '"';
        }

        $monthlyReviews = DB::select($query);
        
        return '<pre>' . json_encode($monthlyReviews) . '</pre>';
    }
}