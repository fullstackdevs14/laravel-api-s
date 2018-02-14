<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class PartnerFirstContact extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    /**
     * Create a new message instance.
     * @param Request $request
     */
    public function __construct
    (
        $request
    )
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->request->has('picture')) {
            $this->attach(storage_path('app/private/' . $this->request->input('picture')), ['as' => 'picture.' . File::extension($this->request->input('picture'))]);
        }
        if ($this->request->has('identity_proof')) {
            $this->attach(storage_path('app/private/' . $this->request->input('identity_proof')), ['as' => 'identity_proof.' . File::extension($this->request->input('identity_proof'))]);
        }
        if ($this->request->has('articles_of_association')) {
            $this->attach(storage_path('app/private/' . $this->request->input('articles_of_association')), ['as' => 'articles_of_association.' . File::extension($this->request->input('articles_of_association'))]);
        }
        if ($this->request->has('registration_proof')) {
            $this->attach(storage_path('app/private/' . $this->request->input('registration_proof')), ['as' => 'registration_proof.' . File::extension($this->request->input('registration_proof'))]);
        }
        if ($this->request->has('address_proof')) {
            $this->attach(storage_path('app/private/' . $this->request->input('address_proof')), ['as' => 'address_proof.' . File::extension($this->request->input('address_proof'))]);
        }
        if ($this->request->has('shareholder_declaration')) {
            $this->attach(storage_path('app/private/' . $this->request->input('shareholder_declaration')), ['as' => 'shareholder_declaration.' . File::extension($this->request->input('shareholder_declaration'))]);
        }

        return $this->from(Config::get('constants.mail_main'), Config::get('constants.company_name'))
            ->subject('Nouveau partenaire')
            ->view('emails.partner_first_contact');
    }

}
