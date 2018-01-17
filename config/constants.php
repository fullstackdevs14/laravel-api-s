<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Defined Variables
    |--------------------------------------------------------------------------
    |
    | This is a set of variables that are made specific to this application
    | that are better placed here rather than in .env file.
    | Use config('your_key') to get the values.
    |
    */

    'debug' => env('APP_DEBUG'),
    'test' => env('APP_TEST'),

    'base_url' => env('BASE_URL', 'https://www.sipper.pro/public/'),
    'base_url_application_user' => env('BASE_URL_USER', 'https://www.sipper.pro/public/uploads/application_users_img/'),
    'base_url_partner' => env('BASE_URL_PARTNER', 'https://www.sipper.pro/public/uploads/partners_img/'),
    'base_url_invoice' => env('BASE_URL_INVOICE', 'https://www.sipper.pro/public/uploads/invoices/'),
    'general_id_application' => env('GENERAL_ID_APPLICATION'),
    'general_wallet_application' => env('GENERAL_WALLET_APPLICATION'),

    'mail_main' => env('MAIL_MAIN'),
    'mail_admin' => env('MAIL_ADMIN'),

    'company_name' => env('COMPANY_NAME'),
    'company_address' => env('COMPANY_ADDRESS'),
    'company_website' => env('COMPANY_WEBSITE'),
    'company_siret' => env('SIRET'),
    'company_legal_form' => env('LEGAL_FORM'),
    'company_tva_number' => env('TVA_NUMBER'),
];