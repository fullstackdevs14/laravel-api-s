<?php

namespace App\Http\Controllers\API\ApplicationUsers;

use App\ApplicationUser;
use App\Handlers\MangoPay\MangoPayHandler;
use App\Http\Controllers\Controller;
use App\Repositories\ApplicationUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use JWTAuth;
use MangoPay\MangoPayApi;
use MangoPay\Pagination;

/**
 * Cette classe gère tout ce qui est relatif à la gestion des cartes bancaires, tant aurpès de l'api application, que de l'api Mangopay.
 *
 * Class MangoPayController
 * @package App\Http\Controllers\API\ApplicationUsers
 */
class MangoPayController extends Controller
{
    /**
     * C'est model.
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
     * Gestionnaire.
     *
     * Contrôle les action des plus courante de la librairie Mangopay.
     *
     * @var MangoPayHandler
     */
    private $mangoPayHandler;

    /**
     * Dépot du model "ApplicationUser".
     *
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * MangoPayController constructor.
     * @param ApplicationUser $applicationUser
     * @param MangoPayApi $mangoPayApi
     * @param MangoPayHandler $mangoPayHandler
     * @param ApplicationUserRepository $applicationUserRepository
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        MangoPayApi $mangoPayApi,
        MangoPayHandler $mangoPayHandler,
        ApplicationUserRepository $applicationUserRepository
    )
    {
        Config::set('jwt.user', ApplicationUser::class);
        Config::set('auth.providers.users.model', ApplicationUser::class);
        $this->applicationUser = $applicationUser;
        $this->mangoPayApi = $mangoPayApi;
        $this->mangoPayHandler = $mangoPayHandler;
        $this->applicationUserRepository = $applicationUserRepository;
    }

    /**
     * Cette fonction gère la première étape d'enregistrement d'une carte bancaire.
     * --> Vérifie que tous les champs nécéssaires sont remplis.
     * --> Vérifie que les infos carte bleue sont présentes (juste un booléen -> les infos de la carte bleue ne passent
     * jamais en brute par le serveur application).
     * --> Si aucun identifiant Mangopay n'est enregistré pour l'utilisateur dans la table "application_users" de la base
     * de données application -> enregistre l'identifiant nouvellement créé et retourné par l'api Mangopay.
     * --> Retourne l'objet "CardRegistration" pour qu'une requête puisse être éffectuée vers le serveur de tokenisation.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerNewCard
    (
        Request $request
    )
    {
        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        if ($applicationUser->mango_id === null) {
            if (
                $request->has([
                    'nationality',
                    'countryOfResidence',
                    'isCardNumber',
                    'isCardExpirationDate',
                    'isCardCvx',
                ])
                AND $request['isCardNumber'] == true
                AND $request['isCardExpirationDate'] == true
                AND $request['isCardCvx'] == true
            ) {
                $mangoUser = $this->mangoPayHandler->createUser(
                    $this->mangoPayApi,
                    $applicationUser->firstName,
                    $applicationUser->lastName,
                    strtotime($applicationUser->birthday),
                    $request['nationality'],
                    $request['countryOfResidence'],
                    $applicationUser->email
                );
                $applicationUserForMangoId = $this->applicationUser->findOrFail($applicationUser->id);
                $applicationUserForMangoId->mango_id = $mangoUser->Id;
                $applicationUserForMangoId->save();

                /**
                 * Récupération d'un objet de CardRegistration.
                 */

                $result = $this->mangoPayHandler->cardPreRegistration(
                    $this->mangoPayApi,
                    $mangoUser->Id,
                    'EUR'
                );
            }

            return response()->json(
                [
                    'data' => $result,
                    'message' => 'Utilisateur enregistré sur MangoPay avec retour des infos carte pour le tokenizer.'
                ], 200);
        }

        /**
         * Uniquement si l'utilisateur avait déjà enregistré une carte bleue sur ce compte.
         */
        if ($applicationUser->mango_id !== null) {
            $result = $this->mangoPayHandler->cardPreRegistration(
                $this->mangoPayApi,
                $applicationUser->mango_id,
                'EUR'
            );

            return response()->json(
                [
                    'data' => $result,
                    'message' => 'Retour des infos carte pour le tokenizer.'
                ], 200);
        }
    }

    /**
     * Cette fonction enregistre la carte définitivement auprès de l'api Mangopay.
     * Il faut au préalable que un objet CardRegistration ait été créé et que les données de la carte ait été envoyée au serveur de tokenisation.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cardRegistration
    (
        Request $request
    )
    {
        $this->validate($request, [
            'Id' => 'required|numeric',
            'data' => 'required|string',
        ]);
        
        $cardRegisterPut = $this->mangoPayApi->CardRegistrations->Get($request['Id']);
        $cardRegisterPut->RegistrationData = isset($request['data']) ? $request['data'] : 'errorCode=' . $request['errorCode'];
        $result = $this->mangoPayApi->CardRegistrations->Update($cardRegisterPut);

        if ($result->Status === "VALIDATED") {
            $applicationUser = $this->applicationUser->where('mango_id', $result->UserId)->get()->first();
            $this->mangoPayHandler->setUsedCardInApplicationBdd(
                $applicationUser,
                $result->CardId
            );
        }

        return response()->json($result, 200);
    }

    /**
     * Cette fonction retourne les cartes qui n'ont pas été désactivées pour un utilisateur.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveCards()
    {
        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        if ($applicationUser->mango_id == null) {
            return response()->json([], 200);
        }

        $pagination = new Pagination(1, 100);
        $result = $this->mangoPayApi->Users->GetCards($applicationUser->mango_id, $pagination);

        $array = [];
        foreach ($result as $card) {
            if ($card->Active === true) {
                array_push($array, $card);
            }
        }

        return response()->json($array, 200);
    }

    /**
     * Cette fonction enregistre une carte comme utilisée dans la base de données application.
     * --> Un identifiant de carte Mangopay est enregistrée dans la base de données api.
     * --> Cet identifiant sera ensuite utilisée lors des paiement pour identifier la carte à utiliser.
     * --> Une vérification est éffectuée au préalable pour ne pas enregistrer une carte désactivée comme carte utilisée.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCardAsUsed
    (
        Request $request
    )
    {
        $this->validate($request, [
            'cardId' => 'string|required'
        ]);

        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        $pagination = new Pagination(1, 100);
        $results = $this->mangoPayApi->Users->GetCards($applicationUser->mango_id, $pagination);

        /**
         * Permet de vérifier qu'une carte n'a pas été désactivée avant de la sélectionnée comme utilisée.
         */
        foreach ($results as $card) {
            if ($card->Id == $request['cardId']) {
                $this->mangoPayHandler->setUsedCardInApplicationBdd($applicationUser, $card->Id);
                return response()->json(['message' => 'Nous avons bien modifié votre moyen de paiement'], 200);
            }
        }
        return response()->json(['error' => 'Cette carte semble avoir été supprimé de votre compte.'], 422);
    }

    /**
     * Cette fonction se charge de désactiver une carte auprès de l'api Mangopay.
     * Si la carte est renseignée comme utilisée dans la base de données application, elle est supprimée.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivateCard
    (
        Request $request
    )
    {
        $this->validate($request, [
            'cardId' => 'string|required'
        ]);

        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        $pagination = new Pagination(1, 100);
        $results = $this->mangoPayApi->Users->GetCards($applicationUser->mango_id, $pagination);

        foreach ($results as $card) {
            if ($card->Id == $request['cardId']) {
                $updatedCard = new \MangoPay\Card();
                $updatedCard->Id = $card->Id;
                $updatedCard->Active = false;
                $this->mangoPayApi->Cards->Update($updatedCard);

                if ($applicationUser->mango_card_id == $request['cardId']) {
                    $applicationUser = $this->applicationUser->findOrFail($applicationUser->id);
                    $applicationUser->mango_card_id = null;
                    $applicationUser->update();

                    return response()->json(['message' => 'La carte a bien été supprimée de votre compte. Vous n\'avez plus de carte séléctionnée pour vos paiements avec ' . Config::get('constants.company_name') . '.'], 200);
                }
                return response()->json(['message' => 'La carte a bien été supprimée de votre compte.'], 200);
            }
        }
        return response()->json(['error' => 'Cette carte semble déjà avoir été supprimé de votre compte.'], 422);
    }

}
