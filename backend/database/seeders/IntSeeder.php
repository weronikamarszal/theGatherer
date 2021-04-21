<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntSeeder extends Seeder

{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $values=[
            ['object_id'=>1,'attribute_id'=>1,'value'=>16],
            ['object_id'=>2,'attribute_id'=>1,'value'=>17],
        ];
        DB::table('value_ints')->insert($values);

    }
}
