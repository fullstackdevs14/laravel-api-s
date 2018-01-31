<?php

namespace App\Http\Controllers\Back_office\Orders;

use App\ApplicationUser;
use App\Handlers\Invoices\VATCalculator;
use App\Http\Controllers\Controller;
use App\MenuCategories;
use App\Order;
use App\OrderInfo;
use App\Partner;
use App\Repositories\PartnerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Cette classe gère le listing et l'affichage du détails des commandes.
 *
 * Class OrderController
 * @package App\Http\Controllers\Back_office\Orders
 */
class OrdersController extends Controller
{
    /**
     * C'est un model.
     *
     * @var Order
     */
    private $order;

    /**
     * C'est un model.
     *
     * @var OrderInfo
     */
    private $orderInfo;

    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux partenaires.
     *
     * @var PartnerRepository
     */
    private $partnerRepository;

    /**
     * C'est le nombre de lignes dans les listes, par page.
     *
     * @var int
     */
    private $nbrPerPage = 15;

    /**
     * OrderController constructor.
     * @param Order $order
     * @param OrderInfo $orderInfo
     * @param ApplicationUser $applicationUser
     * @param PartnerRepository $partnerRepository
     */
    public function __construct
    (
        Order $order,
        OrderInfo $orderInfo,
        ApplicationUser $applicationUser,
        PartnerRepository $partnerRepository
    )
    {
        $this->order = $order;
        $this->orderInfo = $orderInfo;
        $this->applicationUser = $applicationUser;
        $this->partnerRepository = $partnerRepository;
    }

    /**
     * Cette fonction retourne une vue listants toutes les commandes.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index
    (
        Request $request
    )
    {
        $orders = $this->orderInfo->where(function ($query) use ($request) {
            if (($search = $request->get('search'))) {
                $query->orWhere('orderID', 'like', '%' . $search . '%');
            }
        })->orderBy('created_at', 'desc')->paginate($this->nbrPerPage);

        $links = $orders->appends(Input::except('page'))->render();

        return view('activities.orders.index', compact('orders', 'links'));
    }

    /**
     * Cette fonction retourne une vue contenant le détail d'une commande.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show
    (
        $id
    )
    {
        $orderInfo = $this->orderInfo->findOrFail($id);
        $orders = $orderInfo
            ->items()
            ->get()
            ->sortBy('name')
            ->groupBy('category_id');

        $sum = 0;
        $quantity = 0;
        $tax = 0;

        if ($orderInfo['HHStatus'] == 0) {
            foreach ($orders as $category => $items) {
                foreach ($items as $item) {
                    $sum += $item['itemPrice'] * $item['quantity'];
                    $quantity += $item['quantity'];
                    $tax += VATCalculator::get_vat_amount_from_ttc_and_tax(($item['itemPrice'] * $item['quantity']), $item['tax']);
                }
            }
        } else {
            foreach ($orders as $category => $items) {
                foreach ($items as $item) {
                    $sum += $item['itemHHPrice'] * $item['quantity'];
                    $quantity += $item['quantity'];
                    $tax += VATCalculator::get_vat_amount_from_ttc_and_tax(($item['itemHHPrice'] * $item['quantity']), $item['tax']);
                }
            }
        }

        $tax_excluding_sum = $sum - $tax;

        $categories = MenuCategories::all();
        $newOrders = [];
        foreach ($orders as $category => $items) {
            foreach ($categories as $cat) {
                if ($category == $cat->id) {
                    $newOrders[$cat->category] = $items;
                }
            }
        }
        $orders = $newOrders;

        $partner = Partner::findOrFail($orderInfo->partner_id);

        $incident = $orderInfo->incident()->get()->first();

        $applicationUser = $this->applicationUser->findOrFail($orderInfo->applicationUser_id);

        if ($orderInfo->applicationUser_id_share_bill != null) {
            $applicationUser_2 = $this->applicationUser->findOrFail($orderInfo->applicationUser_id_share_bill);
        } else {
            $applicationUser_2 = null;
        }

        return view('activities.orders.show', compact('orderInfo', 'incident', 'partner', 'orders', 'sum', 'quantity', 'tax_excluding_sum', 'applicationUser', 'applicationUser_2'));
    }

}
