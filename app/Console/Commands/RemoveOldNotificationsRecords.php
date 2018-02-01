<?php

namespace App\Console\Commands;

use App\NotificationChecker;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Cette commande est déclenchée tous les jours et a pour but de supprimer les enregistrement de status de notifications de plus de 30 jours.
 *
 * Class RemoveOldNotificationsRecords
 * @package App\Console\Commands
 */
class RemoveOldNotificationsRecords extends Command
{
    /**
     * Correspond au temps en jours avant que les enregitrement de status des notifications soient supprimés.
     */
    const TIME_TO_LIVE_FOR_NOTIFICATIONS_RECORDS = 30;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications_records:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var NotificationChecker
     */
    private $notificationChecker;

    /**
     * Create a new command instance.
     *
     * @param NotificationChecker $notificationChecker
     */
    public function __construct
    (
        NotificationChecker $notificationChecker
    )
    {
        parent::__construct();
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->notificationChecker->where('created_at', '<', Carbon::now()->subDays(RemoveOldNotificationsRecords::TIME_TO_LIVE_FOR_NOTIFICATIONS_RECORDS))->delete();
    }
}
