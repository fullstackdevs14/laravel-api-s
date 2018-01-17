<?php

namespace App\Console\Commands;

use App\ApplicationUserReplaceEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Cette classe se charge de supprimer les emails de remplacement présents dans la table : "application_users_email_replace"
 * depuis un temps supérieur à la constante "TIME_TO_LIVE_FOR_REPLACE_EMAIL_LINKS".
 *
 * Class CheckEmailReplaceTimeToLive
 * @package App\Console\Commands
 */
class CheckEmailReplaceTimeToLive extends Command
{
    /**
     * Correspond au temps en minutes avant que la demande de changement de l'email soit effacée.
     */
    const TIME_TO_LIVE_FOR_REPLACE_EMAIL_LINKS = 120;

    /**
     * Nom et signature de la commande en console.
     *
     * @var string
     */
    protected $signature = 'email_replace:check';

    /**
     * Description de la commande en console.
     *
     * @var string
     */
    protected $description = 'Supprimer les emails de remplacement de la base de données.';

    /**
     * C'est un model.
     *
     * Il est utilisé pour supprimer les remplacements d'email expirés en base de données application.
     *
     * @var ApplicationUserReplaceEmail
     */
    private $applicationUserReplaceEmail;

    /**
     * Créer une nouvelle instance de commande.
     *
     * CheckEmailReplaceTimeToLive constructor.
     * @param ApplicationUserReplaceEmail $applicationUserReplaceEmail
     */
    public function __construct
    (
        ApplicationUserReplaceEmail $applicationUserReplaceEmail
    )
    {
        parent::__construct();
        $this->applicationUserReplaceEmail = $applicationUserReplaceEmail;
    }

    /**
     * Exécute la commande en console.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->applicationUserReplaceEmail
            ->where('created_at', '<', Carbon::now()->subMinutes(CheckEmailReplaceTimeToLive::TIME_TO_LIVE_FOR_REPLACE_EMAIL_LINKS))
            ->delete();
    }

}
