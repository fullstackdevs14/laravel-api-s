<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PartnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('fr_FR');
        DB::table('partners')->delete();

        $array1 = array('pub', 'cocktail', 'liquor', 'club', 'wine');
        $input1 = array_rand($array1, 1);
        $cat = $array1[$input1];

        DB::table('partners')->insert([
            'email' => 'thomas@sipperapp.com',
            'tel' => $faker->e164PhoneNumber(),
            'ownerFirstName' => $faker->firstName,
            'ownerLastName' => $faker->lastName,
            'password' => bcrypt('password'),
            'name' => 'Rosa bonheur sur seine',
            'category' => $cat,
            'address' => 'Port des Invalides, Quai d\'Orsay',
            'city' => 'Paris',
            'postalCode' => '75007',
            'lat' => 48.8631359,
            'lng' => 2.31557470000007,
            'picture' => '1510607888.jpg',
            'openStatus' => rand(0, 1),
            'HHStatus' => rand(0, 1),
            'website' => 'http://rosabonheur.fr/',
            'mango_id' => 37998300,
            'mango_bank_id' => 38925662,
            'fees' => 2,
            'activated' => 1
        ]);

        $array1 = array('pub', 'cocktail', 'liquor', 'club', 'wine');
        $input1 = array_rand($array1, 1);
        $cat = $array1[$input1];

        DB::table('partners')->insert([
            'email' => $faker->email,
            'tel' => $faker->e164PhoneNumber(),
            'ownerFirstName' => $faker->firstName,
            'ownerLastName' => $faker->lastName,
            'password' => bcrypt('password'),
            'name' => 'Brasserie Barbès',
            'category' => $cat,
            'address' => '2 Boulevard Barbès',
            'city' => 'Paris',
            'postalCode' => '75018',
            'lat' => 48.8840189,
            'lng' => 2.349790799999937,
            'picture' => '1510608311.jpeg',
            'openStatus' => rand(0, 1),
            'HHStatus' => rand(0, 1),
            'website' => 'http://www.brasseriebarbes.com/',
            'mango_id' => 37998424,
            'mango_bank_id' => 38925370,
            'fees' => 2,
            'activated' => 1
        ]);

        $array1 = array('pub', 'cocktail', 'liquor', 'club', 'wine');
        $input1 = array_rand($array1, 1);
        $cat = $array1[$input1];

        DB::table('partners')->insert([
            'email' => $faker->email,
            'tel' => $faker->e164PhoneNumber(),
            'ownerFirstName' => $faker->firstName,
            'ownerLastName' => $faker->lastName,
            'password' => bcrypt('password'),
            'name' => 'Pavillon Puebla',
            'category' => $cat,
            'address' => 'Parc des Buttes Chaumont, Avenue Darcel',
            'city' => 'Paris',
            'postalCode' => '75019',
            'lat' => $faker->latitude($min = 48.821332549646634, $max = 48.88910074349772),
            'lng' => $faker->longitude($min = 2.281036376953125, $max = 2.41973876953125),
            'picture' => '1510608705.jpg',
            'openStatus' => rand(0, 1),
            'HHStatus' => rand(0, 1),
            'website' => 'https://www.facebook.com/Pavillonpuebla/',
            'mango_id' => 37998656,
            'fees' => 4,
            'activated' => rand(0, 1)
        ]);

        $array1 = array('pub', 'cocktail', 'liquor', 'club', 'wine');
        $input1 = array_rand($array1, 1);
        $cat = $array1[$input1];

        DB::table('partners')->insert([
            'email' => $faker->email,
            'tel' => $faker->e164PhoneNumber(),
            'ownerFirstName' => $faker->firstName,
            'ownerLastName' => $faker->lastName,
            'password' => bcrypt('password'),
            'name' => 'O\'Sullivan',
            'category' => $cat,
            'address' => '1 Boulevard Montmartre',
            'city' => 'Paris',
            'postalCode' => '75002',
            'lat' => $faker->latitude($min = 48.821332549646634, $max = 48.88910074349772),
            'lng' => $faker->longitude($min = 2.281036376953125, $max = 2.41973876953125),
            'picture' => '1510609139.jpg',
            'openStatus' => rand(0, 1),
            'HHStatus' => rand(0, 1),
            'website' => 'https://www.sipperapp.com',
            'mango_id' => 37998805,
            'fees' => 7,
            'activated' => 1
        ]);

        $array1 = array('pub', 'cocktail', 'liquor', 'club', 'wine');
        $input1 = array_rand($array1, 1);
        $cat = $array1[$input1];


        DB::table('partners')->insert([
            'email' => $faker->email,
            'tel' => $faker->e164PhoneNumber(),
            'ownerFirstName' => $faker->firstName,
            'ownerLastName' => $faker->lastName,
            'password' => bcrypt('password'),
            'name' => 'The Frog & Underground',
            'category' => $cat,
            'address' => '176 Rue Montmartre',
            'city' => 'Paris',
            'postalCode' => '75002',
            'lat' => $faker->latitude($min = 48.821332549646634, $max = 48.88910074349772),
            'lng' => $faker->longitude($min = 2.281036376953125, $max = 2.41973876953125),
            'picture' => '1510609335.jpg',
            'openStatus' => rand(0, 1),
            'HHStatus' => rand(0, 1),
            'website' => 'http://www.frogpubs.com/fr/pub-the-frog-underground-paris-13.php',
            'mango_id' => 37998894,
            'fees' => 4,
            'activated' => rand(0, 1)
        ]);


        $array1 = array('pub', 'cocktail', 'liquor', 'club', 'wine');
        $input1 = array_rand($array1, 1);
        $cat = $array1[$input1];

        $array2 = array('image1.jpg', 'image2.jpg', 'image3.jpg', 'image4.jpg', 'image5.jpg');
        $input2 = array_rand($array2, 1);
        $image = $array2[$input2];

        DB::table('partners')->insert([
            'email' => $faker->email,
            'tel' => $faker->e164PhoneNumber(),
            'ownerFirstName' => $faker->firstName,
            'ownerLastName' => $faker->lastName,
            'password' => bcrypt('password'),
            'name' => 'Le Kiez Biergarten Paris',
            'category' => $cat,
            'address' => '24 Vauvenargues',
            'city' => 'Paris',
            'postalCode' => '75018',
            'lat' => 48.89361040000001,
            'lng' => 2.3333467999999584,
            'picture' => '1510609574.jpg',
            'openStatus' => rand(0, 1),
            'HHStatus' => rand(0, 1),
            'website' => 'https://www.kiez.fr/',
            'mango_id' => 37998969,
            'fees' => 4,
            'activated' => 1
        ]);

        $array1 = array('pub', 'cocktail', 'liquor', 'club', 'wine');
        $input1 = array_rand($array1, 1);
        $cat = $array1[$input1];

        DB::table('partners')->insert([
            'email' => $faker->email,
            'tel' => $faker->e164PhoneNumber(),
            'ownerFirstName' => $faker->firstName,
            'ownerLastName' => $faker->lastName,
            'password' => bcrypt('password'),
            'name' => 'La Recyclerie',
            'category' => $cat,
            'address' => '83 Boulevard Ornano',
            'city' => 'Paris',
            'postalCode' => '75018',
            'lat' => $faker->latitude($min = 48.821332549646634, $max = 48.88910074349772),
            'lng' => $faker->longitude($min = 2.281036376953125, $max = 2.41973876953125),
            'picture' => '1510609851.jpg',
            'openStatus' => rand(0, 1),
            'HHStatus' => rand(0, 1),
            'website' => 'http://www.larecyclerie.com/',
            'mango_id' => 37999079,
            'fees' => 5,
            'activated' => rand(0, 1)
        ]);

    }
}