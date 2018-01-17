<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Cette classe regroupe les méthodes qui permettent d'interagir avec les horaires d'ouverture stockés dans la table : "openings" de la base de données application.
 * Ces horaires d'ouverture sont destinée à pré remplir la balise html select dans les différents formulaires permettant de modifier les horaires dans partenaire.
 *
 * Class OpeningsController
 * @package App\Http\Controllers\Tools
 */
class OpeningsController extends Controller
{

    /**
     * Cette fonction retourne les horaires stocké dans la table "openings" de la base de données application au format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toolGetOpenings(){

        $toolOpenings = DB::table('openings')->get(['openings']);

        $newArray = [];
        $i = 0;

        foreach($toolOpenings as $key => $value){
            array_push($newArray, $value->openings);
            $i++;
        }

        return response()->json( $newArray, 200);
    }

}
