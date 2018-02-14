<?php

namespace App\Http\Requests;

use App\Partner;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    /**
     * Determine if the partner is authorized to make this request.
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
        $partner = Partner::find($this->partner);

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'picture' => 'required|file|mimes:jpg,jpeg,png',
                    'website' => 'active_url|max:255',

                    'ownerFirstName' => 'required|max:100',
                    'ownerLastName' => 'required|max:100',
                    'LegalRepresentativeNationality' => 'required|exists:countries,isoAlpha2Code',
                    'LegalRepresentativeCountryOfResidence' => 'required|exists:countries,isoAlpha2Code',
                    'LegalRepresentativeEmail' => 'required|email',
                    'birthday' => 'required|date',

                    'name' => 'required|max:255|unique:partners',
                    'category' => 'required|exists:partner_categories,category',
                    'tel' => 'required|phone:FR|max:13',
                    'email' => 'required|email|unique:partners',
                    'password' => 'required|min:8|max:30|confirmed',

                    /**
                     * BAR ADDRESS.
                     */
                    'street_number' => '',
                    'route' => 'required',
                    'postalCode' => 'required|max:255',
                    'city' => 'required|max:255',
                    'lat'=> 'required|numeric',
                    'lng' => 'required|numeric',

                    'gaddress_representative' => 'required|string',
                    'street_number_representative' => '',
                    'route_representative' => 'required',
                    'postalCode_representative' => 'required|max:255',
                    'city_representative' => 'required|max:255',
                    'administrative_area_level_2_representative' => 'required|max:255',
                    'country_representative' => 'required|exists:countries,isoAlpha2Code',

                    'gaddress_hq' => 'required|string',
                    'street_number_hq' => '',
                    'route_hq' => 'required',
                    'postalCode_hq' => 'required|max:255',
                    'city_hq' => 'required|max:255',
                    'administrative_area_level_2_hq' => 'required|max:255',
                    'country_hq' => 'required|exists:countries,isoAlpha2Code',

                    'fees' => 'required|numeric|min:0|max:100',
                ];
            }
            case 'PUT':
            {
                return[
                    'email' => 'required|email|max:100|unique:partners,email,'. $partner->id,
                    'ownerFirstName' => 'required|max:100',
                    'ownerLastName' => 'required|max:100',
                    'birthday' => 'date',
                    'name' => 'required|max:255|unique:partners,name,'. $partner->id,
                    'category' => 'required|exists:partner_categories,category',

                    'address' => 'required|max:255',
                    'postalCode' => 'required|max:255',
                    'city' => 'required|max:255',
                    'lat'=> 'required|numeric',
                    'lng' => 'required|numeric',

                    'tel' => 'required|phone:FR|max:13',
                    'picture' => 'file|mimes:jpg,jpeg,png',
                    'website' => 'active_url|max:255',

                    'openStatus' => 'required|boolean',
                    'HHStatus' => 'required|boolean',
                    'activated' => 'required|boolean',

                    'fees' => 'required|numeric|min:0|max:100'
                ];
            }
            case 'PATCH':
            default:break;
        }
    }

}
