<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectionsSeeder extends Seeder

{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $values=[
            ['id'=>1,'description'=>'Kolekcja mieczy polskich','name'=>'Miecze polskie','isPrivate'=>0],
            ['id'=>2,'description'=>'Kolekcja znaczkow niemieckich','name'=>'Znaczki niemieckie','isPrivate'=>0],
        ];
        DB::table('collections')->insert($values);

    }
}
