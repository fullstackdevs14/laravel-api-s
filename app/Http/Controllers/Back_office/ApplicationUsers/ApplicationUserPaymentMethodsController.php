<?php

namespace App\Http\Controllers\Back_office\ApplicationUsers;

use App\ApplicationUser;
use App\Handlers\MangoPay\MangoPayHandler;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use MangoPay\MangoPayApi;
use MangoPay\Pagination;

/**
 * Cette classe à pour rôle de : lister, afficher et désactiver le / les moyean(s) de paiement d'un utilisteur.
 * /!\ Les moyens de paiements sont ajoutés à la base de données application et Mangopay via l'application.
 *
 * Class ApplicationUserPaymentMethodsController
 * @package App\Http\Controllers\Back_office\ApplicationUsers
 */
class ApplicationUserPaymentMethodsController extends Controller
{
    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * C'est un gestionnaire.
     *
     * Gère les actions courantes liées au paiements.
     *
     * @var MangoPayHandler
     */
    private $mangoPayHandler;

    /**
     * Librairie de l'api Mangopay.
     *
     * @var MangoPayApi
     */
    private $mangoPayApi;

    /**
     * ApplicationUserPaymentMethodsController constructor.
     * @param ApplicationUser $applicationUser
     * @param MangoPayHandler $mangoPayHandler
     * @param MangoPayApi $mangoPayApi
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        MangoPayHandler $mangoPayHandler,
        MangoPayApi $mangoPayApi
    )
    {
        $this->applicationUser = $applicationUser;
        $this->mangoPayHandler = $mangoPayHandler;
        $this->mangoPayApi = $mangoPayApi;
    }

    /**
     * Cette fonction retroune une vue listant les moyens de paiement enregistrés pour un utilisteur.
     *
     * @param $applicationUser_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index
    (
        $applicationUser_id
    )
    {
        $applicationUser = $this->applicationUser->findOrFail($applicationUser_id);
        $pagination = new Pagination(1, 100);
        if($applicationUser->mango_id)
        {
            $cards = $this->mangoPayApi->Users->GetCards($applicationUser->mango_id, $pagination);
        } else {
            $cards = [];
        }

        return view('applicationUsers.payment_methods.index', compact('cards', 'applicationUser_id'));
    }

    /**
     * Cette fonction retourne une vue contenant les informatio d'un moyen de paiement en particulier.
     *
     * @param $cardId
     * @param $applicationUser_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show
    (
        $cardId,
        $applicationUser_id
    )
    {
        $card = $this->mangoPayApi->Cards->Get($cardId);
        $card->CreationDate = date('H:i:s - d/m/Y',$card->CreationDate);
        return view('applicationUsers.payment_methods.show', compact('card', 'applicationUser_id'));
    }


    /**
     * Cette fonction désactive la carte sélectionnée dans la base de données Mangopay.
     *
     * @param $id
     * @param $applicationUser_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy
    (
        $id,
        $applicationUser_id
    )
    {
        $updatedCard = new \MangoPay\Card();
        $updatedCard->Id = $id;
        $updatedCard->Active = false;
        $this->mangoPayApi->Cards->Update($updatedCard);

        Session::flash('message', "La carte a bien été désactivée.");

        return redirect()->route('cards.index', $applicationUser_id);
    }

}
