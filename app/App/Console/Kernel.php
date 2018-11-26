<?php

namespace Smartville\App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Smartville\App\Console\Commands\Company\Invoices\SendNewRentInvoiceNotificationCommand;
use Smartville\App\Console\Commands\Company\Invoices\SendNewUtilityInvoiceNotificationCommand;
use Smartville\App\Console\Commands\Company\invoices\SendRentDueReminderNotificationCommand;
use Smartville\App\Console\Commands\Company\invoices\SendRentPastDueReminderNotificationCommand;
use Smartville\App\Console\Commands\Company\Invoices\SendUtilityDueReminderNotificationCommand;
use Smartville\App\Console\Commands\Company\Invoices\SendUtilityPastDueReminderNotificationCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(SendNewUtilityInvoiceNotificationCommand::class)
            ->hourly()
            ->evenInMaintenanceMode();

        $schedule->command(SendNewRentInvoiceNotificationCommand::class)
            ->hourly()
            ->evenInMaintenanceMode();

        $schedule->command(SendUtilityDueReminderNotificationCommand::class, [1])
            ->twiceDaily(9, 13)
            ->evenInMaintenanceMode();

        $schedule->command(SendRentDueReminderNotificationCommand::class, [1])
            ->twiceDaily(9, 13)
            ->evenInMaintenanceMode();

        $schedule->command(SendUtilityPastDueReminderNotificationCommand::class, [1])
            ->twiceDaily(9, 15)
            ->evenInMaintenanceMode();

        $schedule->command(SendRentPastDueReminderNotificationCommand::class, [1])
            ->twiceDaily(9, 15)
            ->evenInMaintenanceMode();

        $schedule->command(SendUtilityDueReminderNotificationCommand::class, [3])
            ->dailyAt('09:00')
            ->evenInMaintenanceMode();

        $schedule->command(SendUtilityDueReminderNotificationCommand::class, [7])
            ->weekly()
            ->evenInMaintenanceMode();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
