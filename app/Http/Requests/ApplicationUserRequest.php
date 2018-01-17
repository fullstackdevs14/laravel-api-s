<?php

namespace App\Http\Requests;

use App\ApplicationUser;
use App\Handlers\Telephone\TelephoneHandler;
use Illuminate\Foundation\Http\FormRequest;
use Image;
use JWTAuth;

class ApplicationUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = ApplicationUser::find($this->applicationUser);

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    //'picture' => 'file|mimes:jpg,jpeg,png|required_without:base64',
                    'picture' => 'file|mimes:jpg,jpeg,png',
                    //'base64' => 'string|required_without:picture',
                    'base64' => 'string',
                    'firstName' => 'required|max:100',
                    'lastName' => 'required|max:100',
                    'email' => 'required|email|max:191|unique:application_users,email|unique:application_users_email_replace,email',
                    'tel' => 'required|phone:FR|unique:application_users',
                    'password' => 'required|min:8|max:191|confirmed',
                    'birthday' => 'required|date',

                    'cgu_cgv_accepted' => 'boolean',

                    'isCardNumber' => 'boolean',
                    'isCardExpirationDate' => 'boolean',
                    'isCardCvx' => 'boolean',
                    'nationality' => 'string|exists:countries,isoAlpha2Code',
                    'countryOfResidence' => 'string|exists:countries,isoAlpha2Code'
                ];
            }
            case 'PUT': {
                return [
                    'picture' => 'file|mimes:jpg,jpeg,png',
                    'firstName' => 'required|max:100',
                    'lastName' => 'required|max:100',
                    'email' => 'required|email|max:191|unique:application_users,email,' . $user->id,
                    'tel' => 'required|phone:FR|unique:application_users,tel,' . $user->id,
                    'birthday' => 'required|date',
                    'activated' => 'boolean'
                ];
            }
            case 'PATCH':
            default:
                break;
        }
    }

    public function all()
    {
        $request = parent::all();

        if (isset($request['tel']) && !empty($request['tel']))
        {
            $request['tel'] = TelephoneHandler::frenchNumberFormat($request['tel']);
        }

        return $request;
    }
}