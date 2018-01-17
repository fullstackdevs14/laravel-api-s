<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Cette classe regroupe les méthodes qui permettent d'interagir avec les taxes stockés dans la table : "taxes" de la base de données application.
 *
 * Class TaxesController
 * @package App\Http\Controllers\Tools
 */
class TaxesController extends Controller
{
    /**
     * Cette fonction retourne les taxes au format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toolGetTaxes(){
        $toolOpenings = DB::table('taxes')->get(['per_cent']);

        return response()->json( $toolOpenings, 200);
    }

}
