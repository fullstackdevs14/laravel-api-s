<?php

namespace App\Http\Controllers\Back_office\ApplicationUsers;

use App\ApplicationUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationUserRequest;
use Illuminate\Support\Facades\Input;
use Image;
use Session;
use Symfony\Component\HttpFoundation\Request;

/**
 * Cette classe gère la lecture et l'écriture des utilisateurs en base de données application via le back office.
 *
 * Class ApplicationUserController
 * @package App\Http\Controllers\Back_office\ApplicationUsers
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
     * C'est le nombre de lignes dans les listes, par page.
     *
     * @var int
     */
    private $nbrPerPage = 15;

    /**
     * ApplicationUserController constructor.
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
     * Cette fonction retourne la vue listant les utilisateurs.
     *
     * @param Request $request
     * @return mixed
     */
    public function index
    (
        Request $request
    )
    {
        $applicationUsers = $this->applicationUser->where(function ($query) use ($request) {
            if (($search = $request->get('search'))) {
                $query->orWhere('email', 'like', '%' . $search . '%');
                $query->orWhere('firstName', 'like', '%' . $search . '%');
                $query->orWhere('lastName', 'like', '%' . $search . '%');
                $query->orWhere('tel', 'like', '%' . $search . '%');
            }
        })
            ->paginate($this->nbrPerPage);

        $links = $applicationUsers->appends(Input::except('page'))->render();

        return view('applicationUsers.index', compact('applicationUsers', 'links'));
    }

    /**
     * Cette fonction retourne le formulaire permettant la création d'un nouvel utilisateur.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('applicationUsers.create');
    }

    /**
     * Cette fonction stocke un nouvel utilisateur en base de données application.
     *
     * @param ApplicationUserRequest $request
     * @return mixed
     */
    public function store
    (
        ApplicationUserRequest $request
    )
    {
        $applicationUser = new $this->applicationUser;

        $applicationUser->password = bcrypt($request['password']);

        $applicationUser->firstName = ucfirst($request['firstName']);
        $applicationUser->lastName = ucfirst($request['lastName']);
        $applicationUser->email = $request['email'];
        $applicationUser->tel = $request['tel'];
        $applicationUser->birthday = $request['birthday'];

        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $filename = time() . '.' . $picture->getClientOriginalExtension();
            Image::make($picture)->save(storage_path('app/public/uploads/application_users_img/' . $filename));

            $applicationUser->picture = $filename;
        }

        $applicationUser->save();

        Session::flash('message', "L'utilisateur " . ucfirst($request->input('firstName')) . " a été enregistré.");

        $applicationUsers = $this->applicationUser->paginate($this->nbrPerPage);

        $links = $applicationUsers->appends(Input::except('page'))->render();

        return redirect()->route('applicationUser.index', compact('applicationUsers', 'links'));
    }

    /**
     * Cette fonction retourne la vue avec l'utilisateur sélectionné.
     *
     * @param $applicationUser_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show
    (
        $applicationUser_id
    )
    {
        $applicationUser = $this->applicationUser->findOrFail($applicationUser_id);

        return view('applicationUsers.show', compact('applicationUser'));
    }

    /**
     * Cette fonction retourne la vue permettant la modification d'un utilisteur.
     *
     * @param $applicationUser_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit
    (
        $applicationUser_id
    )
    {
        $applicationUser = $this->applicationUser->findOrFail($applicationUser_id);

        return view('applicationUsers.edit', compact('applicationUser'));
    }

    /**
     * Cette fonction stocke les modifications pour un utilisateur via le back office.
     *
     * @param ApplicationUserRequest $request
     * @param $applicationUser_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update
    (
        ApplicationUserRequest $request,
        $applicationUser_id
    )
    {
        $applicationUser = $this->applicationUser->findOrFail($applicationUser_id);

        $applicationUser->firstName = ucfirst($request['firstName']);
        $applicationUser->lastName = ucfirst($request['lastName']);
        $applicationUser->email = $request['email'];
        $applicationUser->tel = $request['tel'];
        $applicationUser->birthday = $request['birthday'];
        $applicationUser->activated = $request['activated'];

        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $filename = time() . '.' . $picture->getClientOriginalExtension();
            Image::make($picture)->save(storage_path('app/public/uploads/application_users_img/' . $filename));

            $applicationUser->picture = $filename;
        }

        $applicationUser->save();

        Session::flash('message', "L'utilisateur " . ucfirst($request->input('firstName')) . ' ' . ucfirst($request->input('lastName')) . " a été modifié.");

        return view('applicationUsers.edit', compact('applicationUser'));
    }

    /**
     * Cette fonction supprime l'utilisateur de la base de données application.
     *
     * @param $applicationUser_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy
    (
        $applicationUser_id
    )
    {
        $applicationUser = $this->applicationUser->findOrFail($applicationUser_id);

        $firstName = ucfirst($applicationUser['firstName']);

        $lastName = ucfirst($applicationUser['lastName']);

        $applicationUser->delete();

        Session::flash('message', "L'utilisateur " . $firstName . ' ' . $lastName . " a été supprimé.");

        $applicationUsers = $this->applicationUser->paginate($this->nbrPerPage);

        $links = $applicationUsers->appends(Input::except('page'))->render();

        return redirect()->route('applicationUser.index', compact('applicationUsers', 'links'));
    }

    /**
     * Cette fonction ouvre le portail Mangopay pour afficher des détails concernant un utilisateur.
     *
     * @param $applicationUser_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function showMangoPayUserDetails
    (
        $applicationUser_id
    )
    {
        $applicationUser_id = $this->applicationUser->findOrFail($applicationUser_id);
        if (env('MANGOPAY_ENV') == "PRODUCTION") {
            return redirect('https://dashboard.mangopay.com/Users/' . $applicationUser_id->mango_id);
        } else {
            return redirect('https://dashboard.sandbox.mangopay.com/Users/' . $applicationUser_id->mango_id);
        }
    }

}
