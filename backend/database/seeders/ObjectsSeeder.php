<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjectsSeeder extends Seeder

{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $values=[
            ['id'=>1,'collection_id'=>1,'name'=>'Szabla polska'],
            ['id'=>2,'collection_id'=>1,'name'=>'Miecz Pana Wolodyjowskiego'],
            ['id'=>3,'collection_id'=>2,'name'=>'Znaczek z Tyrolu'],
            ['id'=>4,'collection_id'=>2,'name'=>'Znaczek z Brama Brandenburska'],
        ];
        DB::table('objects')->insert($values);

    }
}
