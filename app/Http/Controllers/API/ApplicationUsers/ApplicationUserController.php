<?php

namespace App\Http\Controllers\API\ApplicationUsers;

use App\ApplicationUser;
use App\Events\ApplicationUserRegisteredEvent;
use App\Handlers\MangoPay\MangoPayHandler;
use App\Handlers\Telephone\TelephoneHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationUserRequest;
use App\Repositories\ApplicationUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Image;
use JWTAuth;
use MangoPay\MangoPayApi;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * Cette classe gère toutes les fonctions qui servent à : enregistrer, logguer, déloguer, authentifier,modifier un
 * utilisateur.
 *
 * Class ApplicationUserController
 * @package App\Http\Controllers\API\ApplicationUsers
 */
class ApplicationUserController extends Controller
{
    /**
     * C'est un model.
     *
     * @var ApplicationUser
     */
    private $applicationUser;

    /**
     * C'est un depôt.
     *
     * Gère les actions courantes liées aux utilisateurs.
     *
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * Librairie de l'api Mangopay.
     *
     * @var MangoPayApi
     */
    private $mangoPayApi;

    /**
     * C'est un gestionnaire.
     *
     * Contrôle les action des plus courante de la librairie Mangopay.
     *
     * @var MangoPayHandler
     */
    private $mangoPayHandler;

    /**
     * ApplicationUserController constructor.
     * @param ApplicationUser $applicationUser
     * @param ApplicationUserRepository $applicationUserRepository
     * @param MangoPayApi $mangoPayApi
     * @param MangoPayHandler $mangoPayHandler
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        ApplicationUserRepository $applicationUserRepository,
        MangoPayApi $mangoPayApi,
        MangoPayHandler $mangoPayHandler
    )
    {
        Config::set('jwt.user', ApplicationUser::class);
        Config::set('auth.providers.users.model', ApplicationUser::class);
        $this->applicationUser = $applicationUser;
        $this->applicationUserRepository = $applicationUserRepository;
        $this->mangoPayApi = $mangoPayApi;
        $this->mangoPayHandler = $mangoPayHandler;
    }

    /**
     * Test si un utilisateur à un token valide.
     * Retourne un messa ge d'erreur à l'interface de l'utilisateur si le token d'authentification est invalide.
     * Test if user have a valid JWTAuth token.
     */
    public function testAuth()
    {
    }

    /**
     * Test si les éléments d'authentification sont valides pour une commande sans touch id.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth
    (
        Request $request
    )
    {
        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        $this->validate($request,
            [
                'password' => 'required'
            ]);

        if (Auth::attempt(['email' => $applicationUser->email, 'password' => $request->password])) {
            return response()->json(
                ['auth' => true], 200
            );
        } else {
            return response()->json(
                ['auth' => 'Mot de passe incorrect :/'], 401
            );
        }
    }

    /**
     * Retourne l'objet utilisateur à l'interface de l'utilisateur.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApplicationUser()
    {
        $applicationUser = $this->applicationUserRepository->getApplicationUserFromTokenWithPreparedPictureUrl();
        return response()->json($applicationUser, 200);
    }

    /**
     * Gère l'inscription d'un utilisateur.
     * --> Les élements qui arrivent ont été validés en partie par la classe "ApplicationUserRequest".
     * --> Vérifie que l'utilisateur à bien 18 ans.
     * --> Vérifie que l'utilisateur a bien accepté les CGU/CGV.
     * --> Enregistre l'image de l'utilisateur, que ce soit du JPG ou du base 64.
     * --> Enregistre l'utilisateur dans la base de données application.
     *
     * --> Enregistre l'utilisateur sur l'api Mangopay si les champs "countryOfResidence" et "nationality" ont été renseignés.
     *
     * --> Enregistre l'utilisateur sur l'api Mangopay si les champs :
     * "nationality",
     * "countryOfResidence",
     * "isCardNumber",
     * "isCardExpirationDate",
     * "isCardCvx",
     * ont été renseignés.
     *
     * --> Déclenche l'évènement "ApplicationUserRegisteredEvent" permettant l'envoi d'un mail de vérification du mail pour validation du compte.
     *
     * --> Retourne le message JSON précisant la validation de l'enregistrement.
     *
     * @param ApplicationUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register
    (
        ApplicationUserRequest $request
    )
    {
        $test = time() - 18 * 31536000;
        $test = date('Y/m/d', $test);

        $this->validate($request,
            [
                'birthday' => 'before:' . $test,
                'cgu_cgv_accepted' => 'accepted'
            ],
            [
                'birthday.before' => 'Il faut avoir plus de dix-huit ans pour commander avec application :/',
                'cgu_cgv_accepted.accepted' => 'Vous devez accepter les CGU / CGV pour utiliser l\'application.'
            ]
        );

        /**
         * Si a un fichier -> stocke le fichier.
         */
        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $filename = time() . '.' . $picture->getClientOriginalExtension();
            Image::make($picture)->save(storage_path('app/public/uploads/application_users_img/' . $filename));
        } else {
            $filename = null;
        }

        /**
         * Si le fichier n'est pas un JPG classique mais une chaîne de caractère encodée en base 64.
         */
        if (isset($request['base64']) && $request['base64'] !== null) {
            $data = $request['base64'];
            $filename = time() . '.jpeg';
            $data = explode(',', $data);
            $data = base64_decode($data[1]);
            file_put_contents(storage_path('app/public/uploads/application_users_img/' . $filename), $data);
        }

        /**
         * Enregistrement en base de données.
         */
        $applicationUser = new ApplicationUser([
            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'email' => $request['email'],
            'tel' => $request['tel'],
            'birthday' => $request['birthday'],
            'password' => bcrypt($request['password']),
            'picture' => $filename,
            'email_token' => false,
            'cgu_cgv_accepted' => $request['cgu_cgv_accepted']
        ]);
        $applicationUser->save();

        /**
         * Enregistrement de l'utilisateur auprès de l'API Mangopay.
         */
        if (
        $request->has([
            'nationality',
            'countryOfResidence'
        ])
        ) {
            $mangoUser = $this->mangoPayHandler->createUser(
                $this->mangoPayApi,
                $request['firstName'],
                $request['lastName'],
                strtotime($request['birthday']),
                $request['nationality'],
                $request['countryOfResidence'],
                $request['email']
            );
            $applicationUserForMangoId = $this->applicationUser->findOrFail($applicationUser->id);
            $applicationUserForMangoId->mango_id = $mangoUser->Id;
            $applicationUserForMangoId->save();
        }
        /**
         * MangoPay enregistrement de la CB.
         */
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
            $result = $this->mangoPayHandler->cardPreRegistration(
                $this->mangoPayApi,
                $mangoUser->Id,
                'EUR'
            );

            /**
             * Envoi d'un email pour la validation de l'email.
             */
            event(new ApplicationUserRegisteredEvent($applicationUser));

            return response()->json([
                'data' => $result,
                'message' => 'Utilisateur enregistré sur ' . Config::get('constants.company_name') . ' et MangoPay.'
            ], 200);
        }

        /**
         * Envoi d'un email pour la validation de l'email.
         */
        event(new ApplicationUserRegisteredEvent($applicationUser));

        return response()->json([
            'message' => "Utilisateur enregistré.",
        ], 201);
    }

    /**
     * Gère la connection d'un utilisateur.
     * --> Valide les données envoyées.
     * --> Création du token avec identification et ajout d'un variable d'identification du type d'ulisateur au token.
     * --> Vérification de l'activation de l'utilisateur.
     * --> Vérification de l'activation de l'email.
     *
     * --> Retourne l'utilisateur et le token d'authentification
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

        /**
         * Création de la variable d'identification du type d'utilisateur : partenaire ou utilisateur.
         */
        $role = ['role' => encrypt('application_user')];
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials, $role)) {
                return response()->json([
                    'error' => 'Email et/ou mot de passe incorrect(s).'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                /**
                 * Echec de la création du token.
                 */
                'error' => 'Erreur serveur, veuillez contacter ' . Config::get('constants.company_name') . ' à : ' . Config::get('constants.mail_main') . ' (creation token impossible)',
            ], 500);
        }

        /**
         * Vérification de l'activation de l'utilisateur.
         */
        $user = Auth::user();
        if ($user) {
            if ($user['activated'] == false) {
                Auth::logout();
                JWTAuth::setToken($token)->invalidate();
                return response()->json([
                    'error' => 'Ce compte semble avoir été désactivé :/',
                ], 401);
            }
        }

        /**
         * Vérification de la validation de l'email.
         */
        $user = Auth::user();
        if ($user) {
            if ($user['email_validation'] == false) {
                Auth::logout();
                JWTAuth::setToken($token)->invalidate();
                return response()->json([
                    'error' => 'Vous n\'avez pas encore confirmé votre email :) Une fois validé vous aurez accès à toutes nos merveilles!',
                ], 401);
            }
        }

        if (isset($user['picture'])) {
            $user['picture'] = Config::get('constants.base_url_application_user') . $user['picture'];
        }

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200);
    }

    /**
     * Gère la déconnexion d'un utilisateur.
     *
     * --> Supprime le token de notification de l'utilisateur de la base de données application.
     * --> Invalide le token d'authentification de l'utilisateur.
     *
     * --> Retourne un message JSON indicant la réussite de la déconnexion.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->applicationUserRepository->getApplicationUserFromToken()->notificationToken()->delete();

        $result = JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json([
            'message' => 'Résultat déstruction token = ' . $result . '.',
        ], 200);
    }

    /**
     * Cette fonction gère la mise à jour d'un utilisateur.
     *
     * --> Vérifie que le numéro de téléphone est bien dans le format +33.
     * --> Valide les information envoyées dans la requête.
     * --> Sauvegarde la photo de profil si celle-ci est pésente.
     * --> Supprime la photo de profil présente sur le serveur.
     * --> Enregistre les informations modifiées sur la base de données application.
     *
     * --> Retourne un message de succès JSON avec l'utilisateur modifié en JSON.
     *
     * TODO -- Mettre en place la modification de l'email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update
    (
        Request $request
    )
    {
        $applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        if (isset($request['tel']) AND !empty($request['tel'])) {
            $request['tel'] = TelephoneHandler::frenchNumberFormat($request['tel']);
        }

        $this->validate($request, [
            'base64' => 'string',
            'firstName' => 'required|max:100',
            'lastName' => 'required|max:100',
            'tel' => 'required|phone:FR|unique:application_users,tel,' . $applicationUser->id,
        ]);

        /**
         * Gère les images jpg/base64.
         */
        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $filename = time() . '.' . $picture->getClientOriginalExtension();
            Image::make($picture)->save(storage_path('app/public/uploads/application_users_img/' . $filename));
        }

        if (isset($request['base64']) && $request['base64'] !== null) {
            $data = $request['base64'];
            $filename = time() . '.jpeg';
            $data = explode(',', $data);
            $data = base64_decode($data[1]);
            file_put_contents(storage_path('app/public/uploads/application_users_img/' . $filename), $data);
        }

        if (isset($request['base64']) AND !empty($request['base64']) OR $request->hasFile('picture')) {
            File::delete(storage_path('app/public/uploads/application_users_img/' . $applicationUser->picture));
            $applicationUser->picture = $filename;
        }

        $applicationUser->firstName = ucfirst($request['firstName']);
        $applicationUser->lastName = ucfirst($request['lastName']);
        $applicationUser->tel = $request['tel'];

        $applicationUser->save();

        if (isset($applicationUser['picture'])) {
            $applicationUser['picture'] = Config::get('constants.base_url_application_user') . $applicationUser['picture'];
        }

        return response()->json([
            'message' => 'Votre profil a bien été modifié :)',
            'user' => $applicationUser,
        ], 200);
    }

}
