<?php

namespace App\Http\Controllers\Back_office\ApplicationUsers;

use App\ApplicationUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Cette classe a pour rôle d'accèder et de gèrer les commandes d'un utilisateur ciblé.
 *
 * Class ApplicationUserOrdersController
 * @package App\Http\Controllers\Back_office\ApplicationUsers
 */
class ApplicationUserOrdersController extends Controller
{
    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * C'est le nombre de lignes dans les listes, par page.
     *
     * @var int
     */
    private $nbrPerPage = 15;

    /**
     * ApplicationUserOrdersController constructor.
     * @param ApplicationUser $applicationUser
     */
    public function __construct
    (
        ApplicationUser $applicationUser
    )
    {
        $this->applicationUser = $applicationUser;
    }

    /**
     * Cette fonction retourne une vue listant les commandes d'un utilisateur.
     *
     * @param Request $request
     * @param $applicationUser_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index
    (
        Request $request,
        $applicationUser_id
    )
    {
        $orders_info = $this->applicationUser->findOrFail($applicationUser_id)
            ->ordersInfo()
            ->where(function($query) use($request){
            if(($search = $request->get('search'))){
                $query->orWhere('orderId', 'like', '%'. $search . '%');
            }
        })
            ->orderBy('created_at', 'desc')
            ->paginate($this->nbrPerPage);

        $links = $orders_info->appends(Input::except('page'))->render();

        return view('applicationUsers.orders.orders_list', compact('orders_info', 'links', 'applicationUser_id'));
    }
    
}
