<?php

namespace App\Http\Controllers\Back_office\MangoPay;

use App\Events\MangoPayHookEvent;
use App\Http\Controllers\Controller;
use App\Repositories\ToolsRepository;
use Config;
use Illuminate\Http\Request;
use MangoPay\Hook;
use MangoPay\Libraries\ResponseException;
use MangoPay\MangoPayApi;
use MangoPay\Pagination;
use Session;

/**
 * Cette classe gère les hooks déclenchés par l'api Mangopay à la demande de l'administrateur.
 *
 * Class HooksController
 * @package App\Http\Controllers\Back_office\MangoPay
 */
class HooksController extends Controller
{
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
     * HooksController constructor.
     * @param MangoPayApi $mangoPayApi
     * @param ToolsRepository $toolsRepository
     */
    public function __construct
    (
        MangoPayApi $mangoPayApi,
        ToolsRepository $toolsRepository
    )
    {
        $this->mangoPayApi = $mangoPayApi;
        $this->toolsRepository = $toolsRepository;
    }

    /**
     * Cette fonction retroune tous les hooks activés sur l'api Mangopay et les liste dans une vue.
     *
     * TODO --  la pagination n'est pas gérée.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pagination = new Pagination(1, 100);
        $hooks = $this->mangoPayApi->Hooks->GetAll($pagination);
        return view('activities.mangopay.hooks.index', compact('hooks'));
    }

    /**
     * Cette fonction retourne un formulaire permettant d'activer un hook de l'api Mangopay.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $hooks = $this->toolsRepository->getHooks();
        return view('activities.mangopay.hooks.create', compact('hooks'));
    }

    /**
     * Cette fonction créer un nouveau hook dans l'api Mangopay.
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
                'eventType' => 'required|exists:mangopay_hooks,hook',
            ]);

        try {
            $hook = New Hook();
            $hook->EventType = $request->eventType;
            $hook->Url = Config::get('constants.base_url') . 'mangoPay_hook_error';
            $this->mangoPayApi->Hooks->Create($hook);

        } catch (ResponseException $e) {
            $error = $e->GetErrorDetails();

            if ($error->Errors->EventType == 'A hook has already been registered for this EventType') {
                Session::flash('error', "Un hook existe d'éjà pour ce type d'évènement.");

                $pagination = new Pagination(1, 100);
                $hooks = $this->mangoPayApi->Hooks->GetAll($pagination);
                return view('activities.mangopay.hooks.index', compact('hooks'));
            }
        }

        Session::flash('message', "Le hook a bien été créé.");

        return redirect()->route('mangoPay.hooks.index');
    }

    /**
     * Cette fonction désactive un hook dans l'api Mangopay.
     *
     * @param $hook_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function disable
    (
        $hook_id
    )
    {
        $hook = new Hook();
        $hook->Id = $hook_id;
        $hook->Tag = "";
        $hook->Url = Config::get('constants.base_url') . 'mangoPay_hook_error';
        $hook->Status = "DISABLED";

        $this->mangoPayApi->Hooks->Update($hook);

        Session::flash('message', "Le hook a bien été mis à jours.");

        return redirect()->route('mangoPay.hooks.index');
    }

    /**
     * Cette fonction réactive un hook qui a été désactivé dans l'api Mangopay.
     *
     * @param $hook_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function enable
    (
        $hook_id
    )
    {
        $hook = new Hook();
        $hook->Id = $hook_id;
        $hook->Tag = "";
        $hook->Url = Config::get('constants.base_url') . 'mangoPay_hook_error';
        $hook->Status = "ENABLED";

        $this->mangoPayApi->Hooks->Update($hook);

        Session::flash('message', "Le hook a bien été mis à jours.");

        return redirect()->route('mangoPay.hooks.index');
    }

    /**
     * Cette fonction est appelée par l'api mangopay et déclenche l'envoi d'un message aux administreurs pour prévenir
     * d'une action suivie par les administrateurs.
     *
     * @param Request $request
     */
    public function triggerErrorEvent
    (
        Request $request
    )
    {
        $params = [
            'resourceId' => $request->RessourceId,
            'eventType' => $request->EventType,
            'date' => $request->Date
        ];
        event(new MangoPayHookEvent($params));
    }

}
