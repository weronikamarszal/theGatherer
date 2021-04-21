<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder

{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            new CollectionsSeeder(),
            new ObjectAttributesSeeder(),
            new ObjectsSeeder(),
        ];

        foreach ($seeders as $i) {
            $i->run();
        }
    }
}
