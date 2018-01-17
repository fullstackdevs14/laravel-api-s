<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ApplicationUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('fr_FR');
        DB::table('application_users')->delete();

        for($i = 0; $i < 10; ++$i)
        {
            $array2 = array('image1.jpg', 'image2.jpg', 'image3.jpg', 'image4.jpg', 'image5.jpg', null);
            $input2 = array_rand($array2, 1);
            $image = $array2[$input2];

            DB::table('application_users')->insert([
                'firstName' => $faker->firstName,
                'lastName' => $faker->lastName,
                'email' => $faker->email,
                'password' => bcrypt('password'),
                'tel' => $faker->e164PhoneNumber(),
                'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'picture' => $image,
                'activated' => rand(0, 1)
            ]);
        }

        DB::table('application_users')->insert([
            'firstName' => 'Fanny',
            'lastName' => 'Canavese',
            'email' => 'fanny@applicationapp.com',
            'password' => bcrypt('password'),
            'tel' => $faker->e164PhoneNumber(),
            'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'picture' => $image,
            'email_validation' => true,
            'mango_id' => 34387275,
            'mango_card_id' => 34387278,
            'activated' => true,
        ]);


        DB::table('application_users')->insert([
            'firstName' => 'test',
            'lastName' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'tel' => $faker->e164PhoneNumber(),
            'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'picture' => $image,
            'email_validation' => true,
            'mango_id' => 34904240,
            'mango_card_id' => 34904242,
            'activated' => true,
        ]);

    }
}
