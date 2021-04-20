<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloatSeeder extends Seeder

{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $values=[
            ['object_id'=>1,'attribute_id'=>2,'value'=>100],
            ['object_id'=>1,'attribute_id'=>3,'value'=>25.3],

            ['object_id'=>2,'attribute_id'=>2,'value'=>123.4],
            ['object_id'=>2,'attribute_id'=>3,'value'=>34.9],

            ['object_id'=>3,'attribute_id'=>4,'value'=>2.5],
            ['object_id'=>3,'attribute_id'=>5,'value'=>1.25],

            ['object_id'=>4,'attribute_id'=>4,'value'=>1.1],
            ['object_id'=>4,'attribute_id'=>5,'value'=>2],
        ];
        DB::table('value_floats')->insert($values);

    }
}
