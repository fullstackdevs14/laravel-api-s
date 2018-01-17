<?php

use Illuminate\Database\Seeder;

class MenuCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_categories')->delete();

        $categories = [
            'Boissons chaudes',
            'Eau Jus et Sodas',
            'Bières',
            'Apéritifs',
            'Cocktails',
            'Digestifs',
            'Vins et Champagnes',
            'Petites faims',
            'Grandes faims'
        ];

        foreach ($categories as $category){
            DB::table('menu_categories')->insert([
                'category' => $category,
            ]);
        }
    }
}
