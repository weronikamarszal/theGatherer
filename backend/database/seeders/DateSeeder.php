<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DateSeeder extends Seeder

{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $values=[
            ['object_id'=>3,'attribute_id'=>6,'value'=>'1954-03-12'],
            ['object_id'=>4,'attribute_id'=>6,'value'=>'1968-07-14'],
        ];
        DB::table('value_dates')->insert($values);

    }
}
