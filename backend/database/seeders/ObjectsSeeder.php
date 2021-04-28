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
            ['id'=>1,'collection_id'=>1,'name'=>'Szabla polska', 'photo_path' => ''],
            ['id'=>2,'collection_id'=>1,'name'=>'Miecz Pana Wolodyjowskiego', 'photo_path' => ''],
            ['id'=>3,'collection_id'=>2,'name'=>'Znaczek z Tyrolu', 'photo_path' => ''],
            ['id'=>4,'collection_id'=>2,'name'=>'Znaczek z Brama Brandenburska', 'photo_path' => ''],
        ];
        DB::table('objects')->insert($values);

    }
}
