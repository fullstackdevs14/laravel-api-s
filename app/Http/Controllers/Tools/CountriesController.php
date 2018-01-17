<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Cette classe regroupe les méthodes qui permettent d'interagir avec les code pays stockés dans la table : "countries" de la base de données application.
 *
 * Class CountriesController
 * @package App\Http\Controllers\Tools
 */
class CountriesController extends Controller
{

    /**
     * Cette fonction retourne les codes pays au format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toolGetCountries()
    {
        $countries = DB::table('countries')->get();

        return response()->json( $countries, 200);
    }


}
