<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PartnersMenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('partners_menus')->delete();

        $partners = \App\Partner::get(['id']);

        foreach ($partners as $id) {

            for($i = 0; $i < 50; ++$i) {

                $faker = Faker::create('fr_FR');

                $array1 = \App\MenuCategories::all()->pluck('id')->toArray();
                $input = array_rand($array1, 1);
                $input1 = $array1[$input];

                $quantity = rand(5, 50);
                
                $price = $faker->randomFloat($nbMaxDecimals = 2, $min = 2, $max = 10);
                $HHPrice = $price - 1.00;

                DB::table('partners_menus')->insert([
                    'partner_id' => $id['id'],
                    'category_id' => $input1,
                    'name' => $faker->safeColorName,
                    'price' => $price,
                    'HHPrice' => $HHPrice,
                    'quantity' => $quantity,
                    'tax' => 20.00,
                    'alcohol' => rand(0,1),
                    'ingredients' => $faker->text($maxNbChars = 200) ,
                    'availability' => rand(0 ,1),
                ]);
            }
        }
    }
}
