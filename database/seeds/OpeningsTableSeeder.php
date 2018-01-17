<?php

use Illuminate\Database\Seeder;

class OpeningsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('openings')->delete();

        DB::table('openings')->insert([
            'openings' => 'Fermé',
        ]);

        DB::table('openings')->insert([
            'openings' => 'Aucun',
        ]);

        $st = null;

        for($i = 0; $i < 24; ++$i) {

            if ($i < 10){
                $st = '0'.$i;
            } else {
                $st = $i;
            }

            DB::table('openings')->insert([
                'openings' => $st.'h00',
            ]);

            DB::table('openings')->insert([
                'openings' => $st.'h30',
            ]);

        }

    }
}
