<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Les commandes Artisan fournies par l'application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\CheckOrders',
        'App\Console\Commands\CheckOrdersShareBill',
        'App\Console\Commands\CheckPasswordResetTimeToLive',
        'App\Console\Commands\SendInvoices',
        'App\Console\Commands\CheckEmailReplaceTimeToLive'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('orders:check')->everyMinute();
        $schedule->command('ordersShareBill:check')->everyMinute();
        $schedule->command('password_reset:check')->everyMinute();
        $schedule->command('email_replace:check')->everyMinute();
        $schedule->command('send:invoices')->monthlyOn(4, '07:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
        //$this->load(__DIR__.'/Commands');
    }

}
