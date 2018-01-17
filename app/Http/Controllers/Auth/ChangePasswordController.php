<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\User;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class ChangePasswordController extends Controller
{

    private $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function changeGet()
    {
        return view('auth.changePassword');
    }

    public function changePost(UserRequest $request){
        $user = $this->user->findOrFail(Auth::user()->id);

        if(Hash::check($request['check_password'], $user->password)) {
            $user->password = bcrypt($request['password']);

            $user->save();

            Session::flash('message', 'Votre mot de passe a bien Ã©tÃ© modifiÃ©.');

            return view('success');

        } else {

            Session::flash('message', 'Votre mot de passe est incorrect.');
            return view('auth.changePassword');
        }

    }
}
