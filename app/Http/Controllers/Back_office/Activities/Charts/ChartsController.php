<?php

namespace App\Http\Controllers\Back_office\Activities\Charts;

use App\ApplicationUser;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderInfo;
use Carbon\Carbon;
use Charts;
use MangoPay\MangoPayApi;

/**
 * Cette classe se charge de créer les graphiques présents dans le back office.
 *
 * Class ChartsController
 * @package App\Http\Controllers\Back_office\Activities\Charts
 */
class ChartsController extends Controller
{
    /**
     * C'est un model.
     *
     * Gère les action courantes liées aux commandes.
     *
     * @var Order
     */
    private $order;

    /**
     * C'est un model.
     *
     * Gère les action courantes liées aux commandes.
     *
     * @var OrderInfo
     */
    private $orderInfo;

    /**
     * Librairie de l'api Mangopay.
     *
     * @var MangoPayApi
     */
    private $mangoPayApi;

    /**
     * ChartsController constructor.
     * @param Order $order
     * @param OrderInfo $orderInfo
     * @param MangoPayApi $mangoPayApi
     */
    public function __construct
    (
        Order $order,
        OrderInfo $orderInfo,
        MangoPayApi $mangoPayApi
    )
    {
        $this->order = $order;
        $this->orderInfo = $orderInfo;
        $this->mangoPayApi = $mangoPayApi;
    }

    /**
     * Cette foncton se charge de rendre les diagrammes pour les vues. Elle éxécute les fonctions privées de cette classe.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        $chart1 = $this->newUsersLastMonth();
        $chart2 = $this->ticketsLastDays();
        $chart3 = $this->marginLastDays();
        $chart4 = $this->revenueLastDays();

        $charts = [
            $chart1,
            $chart2,
            $chart3,
            $chart4
        ];

        foreach ($charts as $chart) {
            $chart->loader(true)->loaderColor('#088A9B');
            $chart->colors(['#088A9B', '#00ff00', '#0000ff']);

        }

        return view('activities.figures.home',
            [
                'chart1' => $chart1,
                'chart2' => $chart2,
                'chart3' => $chart3,
                'chart4' => $chart4
            ]);
    }

    /**
     * Cette fonction retourne un diragemme au format html à intégrer à une vue.
     * Ce diagramme représente le nombre de commandes passées ces derniers jours.
     *
     * @return mixed
     */
    private function ticketsLastDays()
    {
        return Charts::database(OrderInfo::all(), 'area', 'highcharts')
            ->title('Nombre de tickets (semaine)')
            ->elementLabel("Nombre de commande")
            ->lastByDay();
    }

    /**
     * Cette fonction retourne un diragemme au format html à intégrer à une vue.
     * Ce diagramme représente le CA génèrer par les commandes de ces derniers jours.
     *
     * @return mixed
     */
    private function revenueLastDays()
    {
        $revenue = Charts::multi('bar', 'highcharts')
            ->title('Chiffres d\'affaires (semaine)')
            ->elementLabel("Chiffre d'affaires en euro (commande délivrées et sans incident)");

        $dates = $this->orderInfo->where('created_at', '>=', Carbon::today()->subDay(5))
            ->where('accepted', 1)
            ->where('delivered', 1)
            ->where('incident', 0)
            ->get()->groupBy(function ($item) {
                return $item->created_at->format('d-M-y');
            });

        foreach ($dates as $date => $orders) {
            $sum = 0;
            foreach ($orders as $order) {
                $items = $this->order->where('order_id', $order->id)->get();
                if ($order->HHStatus == 1) {
                    foreach ($items as $item) {
                        $sum += $item->itemHHPrice * $item->quantity;
                    }
                } else {
                    foreach ($items as $item) {
                        $sum += $item->itemPrice * $item->quantity;
                    }
                }
            }
            $revenue->dataset($date, [$sum]);
        }
        return $revenue;
    }

    /**
     * Cette fonction retourne un diragemme au format html à intégrer à une vue.
     * Ce diagramme représente le bénéfice génèrer par les commandes de ces derniers jours.
     *
     * @return mixed
     */
    private function marginLastDays()
    {
        $revenue = Charts::multi('bar', 'highcharts')
            ->title('Marge (semaine)')
            ->elementLabel("Marge en euro (commande délivrées et sans incident)");

        $dates = $this->orderInfo->where('created_at', '>=', Carbon::today()->subDay(5))
            ->where('accepted', 1)
            ->where('delivered', 1)
            ->where('incident', 0)
            ->get()->groupBy(function ($item) {
                return $item->created_at->format('d-M-y');
            });

        foreach ($dates as $date => $orders) {
            $sum = 0;
            foreach ($orders as $order) {
                $items = Order::where('order_id', $order->id)->get();
                if ($order->HHStatus == 1) {
                    foreach ($items as $item) {
                        $sum += $item->itemHHPrice * $item->quantity * $order->fees / 100;
                    }
                } else {
                    foreach ($items as $item) {
                        $sum += $item->itemPrice * $item->quantity * $order->fees / 100;
                    }
                }
            }
            $revenue->dataset($date, [$sum]);
        }
        return $revenue;
    }

    /**
     * Cette fonction retourne un diragemme au format html à intégrer à une vue.
     * Ce diagramme représente le nombre de nouveaux utilisateurs de ces derniers jours.
     *
     * @return mixed
     */
    private function newUsersLastMonth()
    {
        return Charts::database(ApplicationUser::all(), 'line', 'highcharts')
            ->title('Nouveaux utilisateurs sur le denier mois')
            ->elementLabel("Nombre de nouveaux utilisateurs")
            ->lastByMonth();
    }

}
