<?php

use Illuminate\Database\Seeder;

class PartnerCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

        public function run()
    {
        DB::table('partner_categories')->delete();

        $categories = [
            'pub',
            'cocktail',
            'liquor',
            'wine',
            'club'
        ];

        foreach ($categories as $category){
            DB::table('partner_categories')->insert([
                'category' => $category,
            ]);
        }
    }
}
