<?php

namespace App\Http\Controllers\API\Partners;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderInfo;
use App\Partner;
use App\Repositories\OrderInfoRepository;
use App\Repositories\PartnerRepository;
use Carbon\Carbon;
use Config;

/**
 * Cette classe gère les diagrammes présents sur l'interface partenaire.
 *
 * Class ChartsController
 * @package App\Http\Controllers\API\Partners
 */
class ChartsController extends Controller
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
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux partenaires.
     *
     * @var PartnerRepository
     */
    private $partnerRepository;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux commandes.
     *
     * @var OrderInfoRepository
     */
    private $orderInfoRepository;

    /**
     * ChartsController constructor.
     * @param Order $order
     * @param OrderInfo $orderInfo
     * @param PartnerRepository $partnerRepository
     * @param OrderInfoRepository $orderInfoRepository
     */
    public function __construct
    (
        Order $order,
        OrderInfo $orderInfo,
        PartnerRepository $partnerRepository,
        OrderInfoRepository $orderInfoRepository
    )
    {
        Config::set('jwt.user', Partner::class);
        Config::set('auth.providers.users.model', Partner::class);

        $this->order = $order;
        $this->orderInfo = $orderInfo;
        $this->partnerRepository = $partnerRepository;
        $this->orderInfoRepository = $orderInfoRepository;
    }

    /**
     * Cette fonction gère la création et la mise en page des diagrammes retournés à l'interface partenaire.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fillCharts()
    {
        $partner = $this->partnerRepository->getPartnerFromToken();

        /**
         * CHART 1.
         */
        $year = $this->orderInfo->where('partner_id', $partner->id)
            ->where('created_at', '>=', Carbon::today()->subDay(360))
            ->where('accepted', 1)
            ->where('delivered', 1)
            ->where('incident', 0)
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('M-Y');
            })
            ->toArray();

        $array = [];

        foreach ($year as $date => $month) {
            $array[$date] = 0;
            foreach ($month as $orderInfo) {
                $orders = $this->order->where('id', $orderInfo['id'])->get();
                foreach ($orders as $order) {
                    if ($orderInfo['HHStatus'] == 1) {
                        $array[$date] += $order['itemHHPrice'] * $order['quantity'];
                    } else {
                        $array[$date] += $order['itemPrice'] * $order['quantity'];
                    }
                }
            }
        }

        $charts['chart1'] = $array;

        /**
         * CHART 2.
         */

        $orderInfo = $this->orderInfo->where('partner_id', $partner->id)
            ->where('accepted', 1)
            ->where('delivered', 1)
            ->where('incident', 0)
            ->get()
            ->toArray();

        $alcohol = 0;
        $noAlcohol = 0;
        foreach ($orderInfo as $order)
        {
            $items =  $this->order->where('id', $order['id'])->get();
            foreach ($items as $item)
            {
                if($item['alcohol'] == 1)
                {
                    $alcohol += $item['quantity'];
                } else {
                    $noAlcohol += $item['quantity'];
                }
            }
        }

        $charts['chart2'] = (object) [
            'alcohol' => round($alcohol / ($alcohol+$noAlcohol) * 100, 2),
            'noAlcohol' => round($noAlcohol / ($alcohol+$noAlcohol) * 100, 2)
        ];

        return response()->json([
            'charts' => $charts
        ],200);
    }

}
