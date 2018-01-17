<?php

use Illuminate\Database\Seeder;

class TaxesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('taxes')->delete();
        DB::table('taxes')->insert([
            'id' => 1,
            'category' => 'Beverages/Food',
            'per_cent' => 10
        ]);
        DB::table('taxes')->insert([
            'id' => 2,
            'category' => 'Beverages',
            'per_cent' => 20
        ]);
    }
}
