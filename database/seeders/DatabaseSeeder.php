<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('monthly_reviews')->insert([
            [ 'title' => 'January Review', 'description' => '=1+1', 'rating' => 'A' ],
            [ 'title' => 'February Review', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt', 'rating' => 'B' ],
            [ 'title' => 'March Review', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt', 'rating' => 'C' ],
            [ 'title' => 'April Review', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt', 'rating' => 'D' ],
            [ 'title' => 'May Review', 'description' => '=2+5+cmd|\' /C calc\'!A0Lorem ipsum dolor sit amet, consectetur adipiscing elit,= sed do eiusmod tempor incididunt', 'rating' => 'E' ],
            [ 'title' => 'June Review', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt=', 'rating' => 'F' ]
        ]);
    }
}