<?php

use Illuminate\Database\Seeder;

class PartnersOpeningsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('partners_openings')->delete();


        $partners = \App\Partner::get(['id']);

        foreach ($partners as $id) {

            $array1 = array('08h00', '09h00', '10h00');
            $input = array_rand($array1, 1);
            $input1 = $array1[$input];

            $array2 = array('12h00', '13h00');
            $input = array_rand($array2, 1);
            $input2 = $array2[$input];

            $array3 = array('14h00', '17h00', '18h00');
            $input = array_rand($array3, 1);
            $input3 = $array3[$input];

            $array4 = array('23h00', '02h00', '05h00');
            $input = array_rand($array4, 1);
            $input4 = $array4[$input];

            DB::table('partners_openings')->insert([
                'partner_id' => $id['id'],
                'monday1' => $input1,
                'monday2' => $input2,
                'monday3' => $input3,
                'monday4' => $input4,
                'tuesday1' => $input1,
                'tuesday2' => $input2,
                'tuesday3' => $input3,
                'tuesday4' => $input4,
                'wednesday1' => $input1,
                'wednesday2' => $input2,
                'wednesday3' => $input3,
                'wednesday4' => $input4,
                'thursday1' => $input1,
                'thursday2' => $input2,
                'thursday3' => $input3,
                'thursday4' => $input4,
                'friday1' => $input1,
                'friday2' => $input2,
                'friday3' => $input3,
                'friday4' => $input4,
                'saturday1' => $input1,
                'saturday2' => $input2,
                'saturday3' => $input3,
                'saturday4' => $input4,
                'sunday1' => $input1,
                'sunday2' => $input2,
                'sunday3' => $input3,
                'sunday4' => $input4,
            ]);
        }
    }
}