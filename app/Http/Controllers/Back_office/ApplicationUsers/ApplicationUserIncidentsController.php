<?php

namespace App\Http\Controllers\Back_office\ApplicationUsers;

use App\ApplicationUser;
use App\Handlers\MangoPay\MangoPayHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncidentsMonitoringRequest;
use App\Incident;
use App\IncidentMonitoring;
use App\OrderInfo;
use App\Refund;
use App\Repositories\ApplicationUserRepository;
use App\Repositories\IncidentRepository;
use App\Repositories\OrderInfoRepository;
use App\Repositories\RefundRepository;
use App\Repositories\ToolsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use MangoPay\MangoPayApi;

/**
 * Cette classe regroupe les méthodes liées aux incidents et aux remboursements des utilisateurs.
 *
 * Class ApplicationUserIncidentsController
 * @package App\Http\Controllers\Back_office\ApplicationUsers
 */
class ApplicationUserIncidentsController extends Controller
{
    /**
     * C'est un model.
     *
     * @var Refund
     */
    private $refund;

    /**
     * C'est un model.
     *
     * @var Incident
     */
    private $incident;

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
     * Librairie de l'api Mangopay.
     *
     * @var MangoPayApi
     */
    private $mangoPayApi;

    /**
     * C'est un dépôt.
     *
     * Gére les actions courantes liées aux outils.
     *
     * @var ToolsRepository
     */
    private $toolsRepository;

    /**
     * C'est un gestionnaire.
     *
     * Gère les actions courantes liées aux paiements.
     *
     * @var MangoPayHandler
     */
    private $mangoPayHandler;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes lièes aux remboursements.
     *
     * @var RefundRepository
     */
    private $refundRepository;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux incidents.
     *
     * @var IncidentRepository
     */
    private $incidentRepository;

    /**
     * C'est dépôt.
     *
     * Gère les action courantes liées au suivi des incidents.
     *
     * @var IncidentMonitoring
     */
    private $incidentMonitoring;

    /**
     * C'est un dépôt.
     *
     * Gère les actions courantes liées aux commandes.
     *
     * @var OrderInfoRepository
     */
    private $orderInfoRepository;

    /**
     * C'est un dépôt.
     *
     * Gère les action courantes liées aux utilisateurs.
     *
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * C'est le nombre de lignes dans les listes, par page.
     *
     * @var int
     */
    private $nbrPerPage = 15;

    /**
     * ApplicationUserIncidentsController constructor.
     * @param Refund $refund
     * @param Incident $incident
     * @param OrderInfo $orderInfo
     * @param ApplicationUser $applicationUser
     * @param MangoPayApi $mangoPayApi
     * @param ToolsRepository $toolsRepository
     * @param MangoPayHandler $mangoPayHandler
     * @param RefundRepository $refundRepository
     * @param IncidentRepository $incidentRepository
     * @param IncidentMonitoring $incidentMonitoring
     * @param OrderInfoRepository $orderInfoRepository
     * @param ApplicationUserRepository $applicationUserRepository
     */
    public function __construct
    (
        Refund $refund,
        Incident $incident,
        OrderInfo $orderInfo,
        ApplicationUser $applicationUser,
        MangoPayApi $mangoPayApi,
        ToolsRepository $toolsRepository,
        MangoPayHandler $mangoPayHandler,
        RefundRepository $refundRepository,
        IncidentRepository $incidentRepository,
        IncidentMonitoring $incidentMonitoring,
        OrderInfoRepository $orderInfoRepository,
        ApplicationUserRepository $applicationUserRepository
    )
    {
        $this->refund = $refund;
        $this->incident = $incident;
        $this->orderInfo = $orderInfo;
        $this->applicationUser = $applicationUser;
        $this->mangoPayApi = $mangoPayApi;
        $this->toolsRepository = $toolsRepository;
        $this->mangoPayHandler = $mangoPayHandler;
        $this->refundRepository = $refundRepository;
        $this->incidentRepository = $incidentRepository;
        $this->incidentMonitoring = $incidentMonitoring;
        $this->orderInfoRepository = $orderInfoRepository;
        $this->applicationUserRepository = $applicationUserRepository;
    }

    /**
     * Cette fonction retoune une vue listant les incidents affectés à un utilisateur.
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
        $orderInfo = $this->applicationUser->findOrFail($applicationUser_id)->ordersInfo()->where(function ($query) use ($request) {
            if (($search = $request->get('search'))) {
                $query->orWhere('orderId', 'like', '%' . $search . '%');
                $query->orderBy('created_at', 'asc');
            }
        })
            ->get();

        $incidents = [];

        foreach ($orderInfo as $order) {
            if ($order->incident == true) {
                $incident = $order->incident()->get()->first();
                if ($incident !== null) {
                    $incidents[$order->orderId] = $incident;
                }
            }
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($incidents);
        $currentPageSearchResults = $col->slice(($currentPage - 1) * $this->nbrPerPage, $this->nbrPerPage)->all();
        $incidents = new LengthAwarePaginator($currentPageSearchResults, count($col), $this->nbrPerPage, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);
        $links = $incidents->appends(Input::except('page'))->render();

        return view('applicationUsers.incidents.index', compact('incidents', 'applicationUser_id', 'links'));
    }

    public function paginate($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Cette fonction retourne le formulaire de création d'un incident pour un utilisateur et une commande ciblée.
     *
     * @param $order_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create
    (
        $order_id
    )
    {
        $excuses = $this->toolsRepository->getActiveExcuses();

        return view('applicationUsers.incidents.create', compact('order_id', 'excuses'));
    }

    /**
     * Cette fonction stocke en base de données application un incident pour une commande ciblée.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'order_id' => 'required|exists:orders_info,id|unique:incidents,order_id',
                'excuse' => 'required|exists:excuses,excuse'
            ]);

        $incident = $this->incidentRepository->newIncident($request['order_id'], $request['excuse']);
        $this->orderInfoRepository->createIncidentFindOrFail($request['order_id']);

        return $this->prepareViewShow($incident->id);
    }

    /**
     * Cette fonction prépare la vue et les paramètres qui la composent pour le détail d'un incident.
     *
     * @param $incident_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function prepareViewShow
    (
        $incident_id
    )
    {
        $excuses = $this->toolsRepository->getActiveExcuses();
        $incident = $this->incident->findOrFail($incident_id);
        $orderInfo = $this->orderInfo->findOrFail($incident->order_id);
        $memories = $this->incidentMonitoring->where('order_id', $incident->order_id)->orderByDesc('created_at')->get();
        $payIn = $this->mangoPayHandler->getPayIn($this->mangoPayApi, $orderInfo);

        return view('applicationUsers.incidents.show', compact('excuses', 'incident', 'payIn', 'orderInfo', 'memories'));
    }

    /**
     * Cette fonction retourne une vue avec le détail d'un incident.
     *
     * @param $incident_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show
    (
        $incident_id
    )
    {
        return $this->prepareViewShow($incident_id);
    }

    /**
     * Cette fonction permet de mettre à jour les détails d'un incident en base de données application.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'order_id' => 'required|exists:orders_info,id|exists:incidents,order_id',
                'excuse' => 'required|exists:excuses,excuse'
            ]);

        $incident = $this->incident->where('order_id', $request['order_id'])->get()->first();

        $this->incidentRepository->updateIncidentMessage($incident->id, $request['excuse']);

        Session::flash('message', "Le motif de l'incident a bien été modifié.");

        return $this->prepareViewShow($incident->id);
    }

    /**
     * Cette fonction permet de créer un nouveau mémo pour un incident en base de données application. Le formulaire pour
     * cette fonction et rendu avec la vue de l'incident (fonction prepareViewShow).
     *
     * @param IncidentsMonitoringRequest $incidentsMonitoringRequest
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newMemo
    (
        IncidentsMonitoringRequest $incidentsMonitoringRequest
    )
    {
        $this->incidentRepository->newMemo
        (
            $incidentsMonitoringRequest->order_id,
            $incidentsMonitoringRequest->message,
            $incidentsMonitoringRequest->email,
            $incidentsMonitoringRequest->phone,
            $incidentsMonitoringRequest->reimburse
        );

        Session::flash('message', "Le mémo a bien été enregistré.");

        Input::replace([
            'message' => null,
            'email' => 0,
            'phone' => 0,
            'reimburse' => 0
        ]);

        return $this->prepareViewShow($incidentsMonitoringRequest->applicationUser_incident_id);
    }

    /**
     * Cette fonction enregistre l'incident comme traité en base de données application.
     *
     * @param $incident_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function incidentHandled
    (
        $incident_id
    )
    {
        $incident = $this->incidentRepository->incidentHandled($incident_id);

        Session::flash('message', "L'incident est bien enregistré comme traité.");

        return $this->prepareViewShow($incident->id);
    }

    /**
     * Cette fonction enregistre l'incident comme ouvert en base de données application.
     *
     * @param $incident_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function incidentOpened
    (
        $incident_id
    )
    {
        $incident = $this->incidentRepository->incidentOpened($incident_id);

        Session::flash('message', "L'incident est bien enregistré comme réouvert.");

        return $this->prepareViewShow($incident->id);
    }

    /**
     * Cette fonction enregistre l'incident comme urgent en base de données application.
     *
     * @param $incident_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function incidentUrgent
    (
        $incident_id
    )
    {
        $incident = $this->incidentRepository->incidentUrgent($incident_id);

        Session::flash('error', "L'incident est bien enregistré comme URGENT.");

        return $this->prepareViewShow($incident->id);
    }

    /**
     * Cette fonction retourne le formulaire servant à déclencher un rembousement à partir du wallet partenaire.
     *
     * --> Récupère :
     * ---> Les détails de la commande,
     * ---> Les détails de l'incident,
     * ---> Les items de la commande.
     *
     * --> Calcul la somme à rembourser et la somme déjà remboursé.
     * --> Retourne la vue permettant le remboursement.
     *
     * @param $order_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function refundShow
    (
        $order_id
    )
    {
        $orderInfo = $this->orderInfo->FindOrFail($order_id);
        $incident = $orderInfo->incident()->get()->first();
        $items = $orderInfo->items()->get();

        $orders = $orderInfo->items()->get();

        $sum = 0;

        foreach ($orders as $order) {
            if ($orderInfo->HHStatus == 1) {
                $sum += $order->itemHHPrice * $order->quantity;
            } else {
                $sum += $order->itemPrice * $order->quantity;
            }
        }

        $refunds = $this->refund->where('order_id', $orderInfo->id)
            ->orderByDesc('created_at')
            ->get();

        $sumRefund = $this->refund->where('order_id', $orderInfo->id)
            ->where('success', 1)
            ->sum('amount');

        $applicationUser_1 = $this->applicationUser->findOrFail($orderInfo->applicationUser_id);
        if ($orderInfo->applicationUser_id_share_bill != null) {
            $applicationUser_2 = $this->applicationUser->findOrFail($orderInfo->applicationUser_id_share_bill);
        } else {
            $applicationUser_2 = null;
        }

        return view('applicationUsers.incidents.refunds.refund', compact('orderInfo', 'items', 'refunds', 'sumRefund', 'sum', 'incident', 'applicationUser_1', 'applicationUser_2'));
    }

    /**
     * Cette fonction déclenche un remboursement à partir du wallet du partenaire. Ce remboursement est limité à la
     * somme versée lors de la commande.
     *
     * --> Valide une partie la requête.
     * --> Récupère les info de commandes et la liste des items.
     * --> Calcul la somme de la commande.
     * --> Calcul la somme déjà remboursée.
     * --> Valide l'input "amount" de la requête en vérifiant que le montal total remboursé n'est pas supérieur au total
     * de la commande.
     * --> Déclenche un remboursement via l'api Mangopay.
     * --> Enregistre un message avec le status du remboursement dans la base de données application.
     * --> Retourne la vue listant les remboursements avec un message de succès ou d'erreur en variable session.
     *
     *
     * @param Request $request
     * @return \MangoPay\Refund
     */
    public function refund
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'order_id' => 'required|exists:orders_info,id',
                'description' => 'required|string|min:0',
            ]);

        $orderInfo = $this->orderInfo->FindOrFail($request->order_id);

        $orders = $orderInfo->items()->get();

        $sum = 0;

        foreach ($orders as $order) {
            if ($orderInfo->HHStatus == 1) {
                $sum += $order->itemHHPrice * $order->quantity;
            } else {
                $sum += $order->itemPrice * $order->quantity;
            }
        }

        $sumRefund = $this->refund->where('order_id', $orderInfo->id)
            ->where('success', 1)
            ->sum('amount');

        $max = $sum - $sumRefund;

        $this->validate($request,
            [
                'amount' => 'required|numeric|min:1|max:' . $max,
            ]);

        $orderInfo = $this->orderInfo->findOrFail($request['order_id']);
        $incident = $orderInfo->incident()->get()->first();
        $applicationUser = $this->applicationUser->findOrFail($orderInfo->applicationUser_id);
        $results = $this->mangoPayHandler->refund($this->mangoPayApi, $orderInfo, $applicationUser, $request->amount);

        $description = '<strong>Message MangoPay : </strong>' . $results->ResultMessage . "<br><strong>Notes : </strong>" . $request->description;

        if ($results->Status === "FAILED") {
            $status = false;
        } else {
            $status = true;
        };

        $this->refundRepository->newRefund(
            $applicationUser->id,
            $orderInfo->partner_id,
            $orderInfo->id,
            $incident->id,
            $request->amount,
            $status,
            $description,
            $results->Id);

        if ($results->Status === "SUCCEEDED") {
            Session::flash('message', "Le remboursement a bien été éffectué.");
            Session::flash('error', "Penser à créer un mémo sur la fiche incident de l'utilisateur pour justifier le remboursement.");
        } else {
            Session::flash('error', "Une erreur est survenue. Voir l'historique de la fiche remboursement (ci-dessous).");
        }

        return redirect()->route('applicationUser_incident_refund.show', ['order_is' => $orderInfo->id]);
    }

    /**
     * Cette fonction déclenche un remboursement à partir du wallet du partenaire pour une note partagée. Ce remboursement
     * est limité à lasomme versée lors de la commande.
     *
     * --> Valide la requête.
     * --> Récupère les info de commandes et la liste des items.
     * --> Calcul la somme de la commande.
     * --> Calcul la somme déjà remboursée.
     * --> Valide l'input "amount" de la requête en vérifiant que le montal total remboursé n'est pas supérieur au total
     * de la commande.
     * --> Déclenche un remboursement via l'api Mangopay.
     * --> Enregistre un message avec le status du remboursement dans la base de données application.
     * --> Retourne la vue listant les remboursements avec un message de succès ou d'erreur en variable session.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function refundSharedBill
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'order_id' => 'required|exists:orders_info,id',
                'applicationUser_id' => 'required|exists:application_users,id',
                'description' => 'required|string|min:0',
            ]);

        $orderInfo = $this->orderInfo->FindOrFail($request->order_id);

        $orders = $orderInfo->items()->get();

        $sum = 0;

        foreach ($orders as $order) {
            if ($orderInfo->HHStatus == 1) {
                $sum += $order->itemHHPrice * $order->quantity;
            } else {
                $sum += $order->itemPrice * $order->quantity;
            }
        }

        $this->validate($request,
            [
                'amount' => 'required|numeric|min:1|max:' . $sum,
            ]);

        $orderInfo = $this->orderInfo->findOrFail($request->order_id);
        $incident = $orderInfo->incident()->get()->first();
        $applicationUser = $this->applicationUser->findOrFail($request->applicationUser_id);

        if ($request['applicationUser_id'] === $orderInfo->applicationUser_id) {
            $payInId = $orderInfo->payInId;
        } elseif ($request['applicationUser_id'] === $orderInfo->applicationUser_id_share_bill) {
            $payInId = $orderInfo->payInId_share_bill;
        } else {
            // TODO -- Vérifier la gestion des erreurs.
            dd('Erreur inconnue');
        }

        $results = $this->mangoPayHandler->refundSharedBill($this->mangoPayApi, $orderInfo, $payInId, $applicationUser, $request->amount);
        $description = '<strong>Message MangoPay : </strong>' . $results->ResultMessage . "<br><strong>Notes : </strong>" . $request->description;

        if ($results->Status === "FAILED") {
            $status = false;
        } else {
            $status = true;
        };

        $this->refundRepository->newRefund(
            $request->applicationUser_id,
            $orderInfo->partner_id,
            $orderInfo->id,
            $incident->id,
            $request->amount,
            $status,
            $description,
            $results->Id);

        if ($results->Status === "SUCCEEDED") {
            Session::flash('message', "Le remboursement a bien été éffectué.");
            Session::flash('error', "Penser à créer un mémo sur la fiche incident de l'utilisateur pour justifier le remboursement.");
        } else {
            Session::flash('error', "Une erreur est survenue. Voir l'historique de la fiche remboursement (ci-dessous).");
        }

        return redirect()->route('applicationUser_incident_refund.show', ['order_is' => $orderInfo->id]);
    }

}
