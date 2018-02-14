<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\PartnerFirstContact;
use App\Repositories\ToolsRepository;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;

class PartnerSubscriptionController extends Controller
{
    /**
     * @var ToolsRepository
     */
    private $toolsRepository;
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * PartnerSubscriptionController constructor.
     * @param ToolsRepository $toolsRepository
     * @param Mailer $mailer
     */
    public function __construct
    (
        ToolsRepository $toolsRepository,
        Mailer $mailer
    )
    {

        $this->toolsRepository = $toolsRepository;
        $this->mailer = $mailer;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForm()
    {
        $partnerCategories = $this->toolsRepository->getPartnerCategories();
        $countries = $this->toolsRepository->getCountries();

        return view('web.partner_subscription_form', compact('partnerCategories', 'countries'));
    }

    /**
     * @param Request $request
     */
    public function store
    (
        Request $request
    )
    {
        $this->validate($request,
            [
                'permanent' => 'required|boolean',
                'start_date' => 'required|date',
                'end_date' => 'date',
                'ownerFirstName' => 'required|string',
                'ownerLastName' => 'required|string',
                'LegalRepresentativeNationality' => 'required|exists:countries,isoAlpha2Code',
                'LegalRepresentativeCountryOfResidence' => 'required|exists:countries,isoAlpha2Code',
                'LegalRepresentativeEmail' => 'required|email',
                'birthday' => 'required|date',

                'street_number_representative' => 'required',
                'route_representative' => 'required',
                'postalCode_representative' => 'required|max:255',
                'city_representative' => 'required|max:255',
                'country_representative' => 'required|exists:countries,isoAlpha2Code',

                'street_number_hq' => 'required',
                'route_hq' => 'required',
                'postalCode_hq' => 'required|max:255',
                'city_hq' => 'required|max:255',
                'country_hq' => 'required|exists:countries,isoAlpha2Code',

                'name' => 'required|max:255|unique:partners',
                'category' => 'required|exists:partner_categories,category',
                'tel' => 'required|phone:FR|max:13',
                'email' => 'required|email|unique:partners',

                'street_number' => '',
                'route' => 'required',
                'postalCode' => 'required|max:255',
                'city' => 'required|max:255',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',

                'picture' => 'required|file|mimes:jpg,jpeg,png',
                'website' => 'required|active_url|max:255',

                'identity_proof' => 'file|mimes:pdf,jpg,jpeg,png|max:5000',
                'articles_of_association' => 'file|mimes:pdf,jpg,jpeg,png|max:5000',
                'registration_proof' => 'file|mimes:pdf,jpg,jpeg,png|max:5000',
                'address_proof' => 'file|mimes:pdf,jpg,jpeg,png|max:5000',
                'shareholder_declaration' => 'file|mimes:pdf,jpg,jpeg,png|max:5000',


                'g-recaptcha-response' => 'required|captcha'
            ]
        );

        File::cleanDirectory(storage_path('app/private'));

        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $file_name = uniqid() . '.' . $picture->getClientOriginalExtension();
            $picture->move(storage_path('app/private/'), $file_name);
            Input::merge(['picture' => $file_name]);
        }

        if ($request->hasFile('identity_proof')) {
            $identity_proof = $request->file('identity_proof');
            $file_name = uniqid() . '.' . $identity_proof->getClientOriginalExtension();
            $identity_proof->move(storage_path('app/private/'), $file_name);
            Input::merge(['identity_proof' => $file_name]);
        }

        if ($request->hasFile('articles_of_association')) {
            $articles_of_association = $request->file('articles_of_association');
            $file_name = uniqid() . '.' . $articles_of_association->getClientOriginalExtension();
            $articles_of_association->move(storage_path('app/private/'), $file_name);
            Input::merge(['articles_of_association' => $file_name]);
        }

        if ($request->hasFile('registration_proof')) {
            $registration_proof = $request->file('registration_proof');
            $file_name = uniqid() . '.' . $registration_proof->getClientOriginalExtension();
            $registration_proof->move(storage_path('app/private/'), $file_name);
            Input::merge(['registration_proof' => $file_name]);
        }

        if ($request->hasFile('address_proof')) {
            $address_proof = $request->file('address_proof');
            $file_name = uniqid() . '.' . $address_proof->getClientOriginalExtension();
            $address_proof->move(storage_path('app/private/'), $file_name);
            Input::merge(['address_proof' => $file_name]);
        }

        if ($request->hasFile('shareholder_declaration')) {
            $shareholder_declaration = $request->file('shareholder_declaration');
            $file_name = uniqid() . '.' . $shareholder_declaration->getClientOriginalExtension();
            $shareholder_declaration->move(storage_path('app/private/'), $file_name);
            Input::merge(['shareholder_declaration' => $file_name]);
        }

        $this->mailer
            ->to('thomas@sipperapp.com')
            ->send(new PartnerFirstContact($request));

        $message = 'Demande envoyée :)';
        return view('applicationUsers_not_back_office.message', compact('message'));

    }
}
