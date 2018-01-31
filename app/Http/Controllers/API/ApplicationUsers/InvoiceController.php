<?php

namespace App\Http\Controllers\API\ApplicationUsers;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationUserSendInvoicesForOrder;
use App\OrderInfo;
use App\Repositories\ApplicationUserRepository;
use Illuminate\Mail\Mailer;
use Symfony\Component\HttpFoundation\Request;

class InvoiceController extends Controller
{
    /**
     * @var ApplicationUserRepository
     */
    private $applicationUserRepository;

    /**
     * @var OrderInfo
     */
    private $orderInfo;

    /**
     * @var $applicationUser
     */
    private $applicationUser;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * InvoiceController constructor.
     * @param ApplicationUserRepository $applicationUserRepository
     * @param OrderInfo $orderInfo
     * @param Mailer $mailer
     */
    public function __construct
    (
        ApplicationUserRepository $applicationUserRepository,
        OrderInfo $orderInfo,
        Mailer $mailer
    )
    {
        $this->applicationUserRepository = $applicationUserRepository;
        $this->orderInfo = $orderInfo;
        $this->mailer = $mailer;
    }

    public function sendInvoicesByMail
    (
        Request $request
    )
    {
        $this->applicationUser = $this->applicationUserRepository->getApplicationUserFromToken();

        /**
         * Vérification que la commande appartient bien à l'utilisateur.
         */
        $result = $this->orderInfo->where(function ($query) use ($request) {
            $query->orWhere('applicationUser_id', $this->applicationUser->id);
            $query->orWhere('applicationUser_id_share_bill', $this->applicationUser->id);
        })->where('id', $request['order_id'])->get()->isEmpty();

        if ($result == true) {
            return response()->json([
                'error' => 'Une erreur inattendue s\'est produite :/'
            ], 401);
        }

        $this->orderInfo = $this->orderInfo->findOrFail($request['order_id']);

        $this->mailer
            ->to($this->applicationUser->email)
            ->send(new ApplicationUserSendInvoicesForOrder($this->applicationUser, $this->orderInfo));

        //event(new ApplicationUserSendInvoicesForOrderEvent());

        return response()->json([
            'message' => 'Les factures concernant cette commande vous sont envoyées par email.'
        ], 200);

    }

}
