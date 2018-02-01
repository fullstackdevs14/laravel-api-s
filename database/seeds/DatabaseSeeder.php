<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        DB::table('users')->delete();

        DB::table('users')->insert([
            'name' => 'Thomas Bourcy',
            'email' => 'thomas@sipperapp.com',
            'password' => bcrypt('Sipper1617'),
        ]);
        DB::table('users')->insert([
            'name' => 'Fanny Canavese',
            'email' => 'fanny@sipperapp.com',
            'password' => bcrypt('Sipper1617'),
        ]);

        //$this->call(UsersTableSeeder::class);

        //$this->call(ApplicationUsersTableSeeder::class);
        $this->call(PartnersTableSeeder::class);
        $this->call(TaxesTableSeeder::class);
        $this->call(MenuCategoriesTableSeeder::class);
        $this->call(PartnersOpeningsTableSeeder::class);
        $this->call(PartnersMenusTableSeeder::class);
        //$this->call(OrdersTableSeeder::class);
        $this->call(OpeningsTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(MangoPayCurrenciesTableSeeder::class);
        $this->call(PartnerCategoriesTableSeeder::class);

        $this->call(ExcusesTableSeeder::class);

        $this->call(MangoPayHooksTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
