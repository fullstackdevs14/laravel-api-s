<?php

namespace App\Http\Controllers\Back_office\MangoPay;

use App\Http\Controllers\Controller;
use MangoPay\MangoPayApi;
use MangoPay\Pagination;

/**
 * Class DisputesController
 * @package App\Http\Controllers\Back_office\MangoPay
 */
class DisputesController extends Controller
{
    /**
     * Librairie de l'api Mangopay.
     *
     * @var MangoPayApi
     */    private $mangoPayApi;

    /**
     * DisputesController constructor.
     * @param MangoPayApi $mangoPayApi
     */
    public function __construct
    (
        MangoPayApi $mangoPayApi
    )
    {
        $this->mangoPayApi = $mangoPayApi;
    }

    /**
     * Cette fonction retourne une vue contenant toutes les contestations enregistrées par l'api Mangpay.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pagination = new Pagination(1, 100);
        $disputes = $this->mangoPayApi->Disputes->GetAll($pagination);

        return view('activities.mangopay.disputes.index', compact('disputes'));
    }

    /**
     * Cette fonction retoune une vue contenant le détail d'une contestation provenant de l'api Mangopay.
     *
     * @param $dispute_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show
    (
        $dispute_id
    )
    {
        $dispute = $this->mangoPayApi->Disputes->Get($dispute_id);

        return view('activities.mangopay.disputes.show', compact('dispute'));
    }

    /**
     * Cette fonction renvoi la page de l'api Mangopay permettant de gèrer la contestation choisie.
     *
     * @param $dispute_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function submit
    (
        $dispute_id
    )
    {
        if(env('MANGOPAY_ENV') == "PRODUCTION"){
            return redirect('https://dashboard.mangopay.com/Disputes/' . $dispute_id);
        } else {
            return redirect('https://dashboard.sandbox.mangopay.com/Disputes/' . $dispute_id);
        }
    }

}
