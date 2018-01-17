<?php

use Illuminate\Database\Seeder;

class ExcusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('excuses')->delete();

        $excuses = [
            'Commande acceptée mais non délivrée - notification non reçue',
            'Commande acceptée mais non délivrée - mail non reçu',
            'Commande acceptée mais non délivrée - mail et notification non reçus',
            'Commande acceptée mais non délivrée - ivresse manifeste du client',
            'Commande acceptée mais non délivrée - comportement inadéquat du client',
            'Commande acceptée mais non délivrée - ingrédients indisponibles',
            'Commande acceptée mais non délivrée - oubli du barman',
            'Commande acceptée mais non délivrée - commande passée lorsque l\'établissement est fermé',
            'Commande acceptée mais non délivrée - autre',
            'Commande déclinée - ivresse manifeste du client',
            'Commande déclinée - comportement inadéquat du client',
            'Commande déclinée - ingrédients indisponibles',
            'Commande déclinée - manque de temps',
            'Commande déclinée - autre',
            'Autre',
            'Erreur MANGOPAY'
        ];

        foreach ($excuses as $excuse){
            DB::table('excuses')->insert([
                'excuse' => $excuse,
                'active' => 1
            ]);
        }
    }
}
