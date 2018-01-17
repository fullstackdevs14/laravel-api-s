<?php

namespace App\Http\Controllers\Back_office;

use Illuminate\Routing\Controller;

/**
 * Gère l'affichage à l'ouverture de l'application.
 *
 * Class HomeController
 * @package App\Http\Controllers\Back_office
 */
class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
    }

    /**
     * Cette fonction retourne la vue du dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

}