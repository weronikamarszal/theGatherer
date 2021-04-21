<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjectAttributesSeeder extends Seeder

{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $values=[
            ['collection_id'=>1,'id'=>1, 'label'=>'Wiek', 'type'=>'1'],
            ['collection_id'=>1,'id'=>2, 'label'=>'Dlugosc ostrza', 'type'=>'2'],
            ['collection_id'=>1,'id'=>3, 'label'=>'Dlugosc rekojesci', 'type'=>'2'],
            ['collection_id'=>2,'id'=>4, 'label'=>'Szerokosc', 'type'=>'2'],
            ['collection_id'=>2,'id'=>5, 'label'=>'Wysokosc', 'type'=>'2'],
            ['collection_id'=>2,'id'=>6, 'label'=>'Data wydania', 'type'=>'4'],
        ];
        DB::table('object_attributes')->insert($values);

    }
}
