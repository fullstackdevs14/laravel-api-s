<?php

use Illuminate\Database\Seeder;

class MangoPayCurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mangopay_currencies')->delete();

        $currencies = [
            'EUR' => 'Euro',
            'GBP' => 'Pound Sterling',
            'SEK' => 'Swedish Krona',
            'NOK' => 'Norwegian Krone',
            'DKK' => 'Danish Krone',
            'CHF' => 'Swiss Franc',
            'PLN' => 'Polish Zloty',
            'USD' => 'US Dollar (BETA)',
            'CAD' => 'Canadian Dollar (BETA)',
            'AUD' => 'Australian Dollar (BETA)'];

        foreach ($currencies as $key => $value){
            DB::table('mangopay_currencies')->insert([
                'symbol' => $key,
                'name' => $value,
            ]);
        }
    }
}
