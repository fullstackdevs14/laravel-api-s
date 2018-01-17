<?php

namespace App\Http\Controllers\Back_office\MangoPay;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Input;
use MangoPay\MangoPayApi;
use MangoPay\Pagination;

/**
 *
 *
 * Class EventsController
 * @package App\Http\Controllers\Back_office\MangoPay
 */
class EventsController extends Controller
{
    /**
     * Librairie de l'api Mangopay.
     *
     * @var MangoPayApi
     */
    private $mangoPayApi;

    /**
     * EventsController constructor.
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
     * Cette fonction retourne une vue listant les derniers évènements importés depuis l'api Mangopay.
     *
     * TODO --  la pagination n'est pas gérée.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pagination = new Pagination(1, 100);

        $events = $this->mangoPayApi->Events->GetAll($pagination);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($events);
        $perPage = 15;
        $currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $events = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage, $currentPage,['path' => LengthAwarePaginator::resolveCurrentPath()] );
        $links = $events->appends(\Illuminate\Support\Facades\Input::except('page'))->render();
        return view('activities.mangopay.events.index', compact( 'events', 'links'));
    }

}
