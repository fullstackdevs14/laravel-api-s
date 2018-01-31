<?php

namespace App\Mail;

use App\ApplicationUser;
use App\ApplicationUserInvoice;
use App\OrderInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ApplicationUserSendInvoicesForOrder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var ApplicationUser
     */
    public $applicationUser;
    /**
     * @var OrderInfo
     */
    public $orderInfo;
    /**
     * @var ApplicationUserInvoice
     */
    private $applicationUserInvoice;

    /**
     * ApplicationUserSendInvoicesForOrder constructor.
     * @param ApplicationUser $applicationUser
     * @param OrderInfo $orderInfo
     */
    public function __construct
    (
        ApplicationUser $applicationUser,
        OrderInfo $orderInfo
    )
    {
        $this->applicationUser = $applicationUser;
        $this->orderInfo = $orderInfo;
    }

    private function getInvoices
    (
        ApplicationUser $applicationUser,
        OrderInfo $orderInfo
    )
    {
        $invoices = ApplicationUserInvoice::where('applicationUser_id', $applicationUser->id)
            ->where('order_id', $orderInfo->id)
            ->get()
            ->toArray();

        return $invoices;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invoices = $this->getInvoices($this->applicationUser, $this->orderInfo);

        foreach ($invoices as $invoice) {
            $this->attach(storage_path('app/public/uploads/invoices/application_users/' . $invoice['invoice_id'] . '.pdf'));
        }

        return $this->from(Config::get('constants.mail_main'), Config::get('constants.company_name'))
            ->subject('Factures')
            ->view('emails.applicationUser_send_invoices_for_order');
    }
}
