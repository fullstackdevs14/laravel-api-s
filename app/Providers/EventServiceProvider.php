<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ApplicationUserRegisteredEvent' => [
            'App\Listeners\ApplicationUserEmailActivationListener',
        ],
        'App\Events\OrderHandledEvent' => [
            'App\Listeners\ApplicationUserEmailPurchaseConfirmationListener',
        ],
        'App\Events\MangoPayHookEvent' => [
            'App\Listeners\MangoPayEmailHookListener',
        ],
        'App\Events\ApplicationUserResetPasswordEvent' => [
            'App\Listeners\ApplicationUserResetPasswordListener',
        ],
        'App\Events\PartnerBankTransferEvent' => [
            'App\Listeners\PartnerBankTransferListener',
        ],
        'App\Events\PartnerSendInvoiceEvent' => [
            'App\Listeners\PartnerSendInvoiceListener',
        ],
        'App\Events\ApplicationUserReplaceEmailEvent' => [
            'App\Listeners\ApplicationUserReplaceEmailListener',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
