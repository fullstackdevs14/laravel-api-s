<?php

namespace App\Http\Controllers\API\Partners;

use App\Http\Controllers\Controller;
use App\Partner;
use App\Repositories\PartnerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class PartnerController extends Controller
{
    /**
     * C'est un model.
     *
     * @var Partner
     */
    private $partner;

    /**
     * C'est un dépôt.
     *
     * Gère les action courantes liées aux partenaires.
     *
     * @var PartnerRepository
     */
    private $partnerRepository;

    /**
     * PartnerController constructor.
     * @param Partner $partner
     * @param PartnerRepository $partnerRepository
     */
    public function __construct
    (
        Partner $partner,
        PartnerRepository $partnerRepository
    )
    {
        Config::set('jwt.user', Partner::class);
        Config::set('auth.providers.users.model', Partner::class);

        $this->partner = $partner;
        $this->partnerRepository = $partnerRepository;
    }

    /**
     * Cette fonction gère la connexion d'un partenaire.
     *
     * --> Validation de la requête : email et mot de passe.
     * --> Création du token d'authentification.
     * --> Vérification que le partenaire est activé.
     * --> Prépare le partenaire en JSON et le retourne à l'interface partenaire.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login
    (
        Request $request
    )
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        /**
         * Création du token.
         */
        try {
            $role = ['role' => encrypt('partner')];
            if (!$token = JWTAuth::attempt($credentials, $role)) {
                return response()->json([
                    'error' => 'Email et/ou mot de passe incorrect(s).'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Erreur interne, veuillez contacter ' . Config::get('constants.company_name') . ' à : ' . Config::get('constants.mail_main'),
            ], 500);
        }

        /**
         * Vérifie que le partenaire est est activé.
         */
        $partner = Auth::user();
        if ($partner) {
            if ($partner['activated'] == false) {
                Auth::logout();
                JWTAuth::setToken($token)->invalidate();
                return response()->json([
                    'error' => 'Ce compte semble avoir été désactivé :/',
                ], 401);
            }
        }

        if (isset($partner['picture'])) {
            $partner['picture'] = Config::get('constants.base_url_partner') . $partner['picture'];
        }

        return response()->json([
            'token' => $token,
            'partner' => $partner
        ], 200);
    }

    /**
     * Cette fonction gère la déconnexion d'un partenaire.
     *
     * --> Invalide le toke d'authentification.
     * --> Retourne un message JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $result = $this->partnerRepository->invalidateToken();
        return response()->json([
            'message' => 'Résultat déstruction token = ' . $result . '.',
        ], 200);
    }

    /**
     * Cette fonction retourne un partenaire au format JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPartner()
    {
        $partner = $this->partnerRepository->getPartnerFromTokenWithPreparedPictureUrl();
        return response()->json(['partner' => $partner], 200);
    }

    /**
     * Cette fonction se charge dee mettre à jour un partenaire dans la base de données application suite à une requête
     * provenant de l'interface partenaire.
     *
     * --> Retourne un message JSON de succès.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update
    (
        Request $request
    )
    {
        $partner = $this->partnerRepository->getPartnerFromToken();

        $this->validate($request, [
            'email' => 'required|email|max:100|unique:partners,email,' . $partner->id,
            'tel' => 'required|phone:FR|max:13',
            'website' => 'active_url|max:255',
        ]);

        $partner->email = $request['email'];
        $partner->tel = $request['tel'];
        $partner->website = $request['website'];
        $partner->save();

        return response()->json(['message' => 'Vos modifications ont bien été mises à jour.'], 200);
    }

    /**
     * Cette fonction se charge de mettre à jour le statut d'ouverture du partenaire suite à une requête provenant de
     * l'interface partenaire.
     *
     * --> Retourne un message JSON de succès.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOpenStatus()
    {
        $partner = $this->partnerRepository->getPartnerFromToken();

        if ($partner->openStatus == 1) {
            $partner->openStatus = 0;
            $partner->save();
            return response()->json(['message' => 'La caisse ' . Config::get('constants.company_name') . ' a bien été fermée.'], 200);
        } else {
            $partner->openStatus = 1;
            $partner->save();
            return response()->json(['message' => 'La caisse ' . Config::get('constants.company_name')  . 'a bien été ouverte.'], 200);
        }
    }

    /**
     * Cette fonction se charge de mettre à jour le statut d'happy hour du partenaire suite à une requête provenant de
     * l'interface partenaire.
     *
     * --> Retourne un message JSON de succès.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHHStatus()
    {
        $partner = $this->partnerRepository->getPartnerFromToken();

        if ($partner->HHStatus == 1) {
            $partner->HHStatus = 0;
            $partner->save();
            return response()->json(['message' => 'L\'Happy Hour ' . Config::get('constants.company_name') . ' a bien été fermé.'], 200);
        } else {
            $partner->HHStatus = 1;
            $partner->save();
            return response()->json(['message' => 'L\'Happy Hour ' . Config::get('constants.company_name') . ' a bien été ouvert.'], 200);
        }
    }

    /**
     * Cette fonction se charge de déclencher l'envoi d'un mail de la part d'un partenaire aux administrateur pour modifier des
     * informations non modifiables à partir de l'interface partenaire.
     *
     * --> Validation de la requête.
     * --> Récupèration du partenaire à partir du token d'authentification.
     * --> Envoi de l'email avec les information (message  / titre) contenues dans la requête.
     * --> Retourne un message JSON de succès.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function modificationMessage
    (
        Request $request
    )
    {
        $this->validate($request, [
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $this->partner = $this->partnerRepository->getPartnerFromToken();

        Mail::send('emails.partner_modification',
            [
                'subject' => $request->subject,
                'body' => $request->body,
                'partner' => $this->partner
            ], function ($message) {
                $message->to(Config::get('constants.mail_main'))->subject('Message de demande de modification(s) du partenaire : ' . $this->partner->name);
            });

        return response()->json(['message' => 'La demande de modification(s) a bien été envoyée.'], 200);
    }

}
