<?php

namespace App\Console\Commands;

use App\ApplicationUserResetPassword;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Cette commande est déclenchée toutes les minutes et a pour but de supprimer les demandes expirées de réinitialisation du mot de passe.
 *
 * Class CheckPasswordResetTimeToLive
 * @package App\Console\Commands
 */
class CheckPasswordResetTimeToLive extends Command
{

    /**
     * Correspond au temps en minutes avant que la demande de réinitialisation du mot de passe soit effacée.
     */
    const TIME_TO_LIVE_FOR_RESET_PASSWORD_LINKS = 120;

    /**
     * Nom et signature de la commande en console.
     *
     * @var string
     */
    protected $signature = 'password_reset:check';

    /**
     * Description de la commande console.
     *
     * @var string
     */
    protected $description = 'Supprime les demande de réinitialisation du mot de passe supérieures à la constante TIME_TO_LIVE_FOR_RESET_PASSWORD_LINKS.';

    /**
     * Injecté via le contrôleur.
     * C'est un model.
     * Il sert a effectuer la requête de suppression vers la base de données.
     *
     * @var ApplicationUserResetPassword
     */
    private $applicationUserResetPassword;

    /**
     * Créé une nouvelle instance de la commande.
     *
     * CheckPasswordResetTimeToLive constructeur.
     * @param ApplicationUserResetPassword $applicationUserResetPassword
     */
    public function __construct
    (
        ApplicationUserResetPassword $applicationUserResetPassword
    )
    {
        parent::__construct();
        $this->applicationUserResetPassword = $applicationUserResetPassword;
    }

    /**
     * Exécute les instructions de la commande en console.
     * Supprime les demandes de réinitialisation qui vont au delà de TIME_TO_LIVE_FOR_RESET_PASSWORD_LINKS.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->applicationUserResetPassword
            ->where('created_at', '<', Carbon::now()->subMinutes(CheckPasswordResetTimeToLive::TIME_TO_LIVE_FOR_RESET_PASSWORD_LINKS))
            ->delete();
    }

}
