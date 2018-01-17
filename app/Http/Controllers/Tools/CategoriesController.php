<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * Cette classe regroupe les méthodes qui permettent d'interagir avec les catégories stockées dans la table : "categories" de la base de données application.
 *
 * Class CategoriesController
 * @package App\Http\Controllers\Tools
 */
class CategoriesController extends Controller
{

    /**
     * Cette fonction retourne les catégories sotckées dans la base de données application au format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toolGetActiveCategories()
    {

        $toolCategories = DB::table('menu_categories')->where('active', '=', 1)->get(['category']);

        return response()->json($toolCategories, 200);
    }
}
