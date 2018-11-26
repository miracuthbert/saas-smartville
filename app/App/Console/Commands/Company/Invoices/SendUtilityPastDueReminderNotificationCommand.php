<?php

namespace Smartville\App\Console\Commands\Company\Invoices;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Smartville\App\Services\UtilityInvoiceNotifier;

class SendUtilityPastDueReminderNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'utility:past {days=1} {time?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send utility invoice past due reminder notification to users';
    /**
     * @var UtilityInvoiceNotifier
     */

    /**
     * The Utility Invoice Notifier Service.
     *
     * @var UtilityInvoiceNotifier
     */
    protected $notifier;

    /**
     * Create a new command instance.
     *
     * @param UtilityInvoiceNotifier $notifier
     */
    public function __construct(UtilityInvoiceNotifier $notifier)
    {
        parent::__construct();

        $this->notifier = $notifier;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $days = $this->argument('days');
        $time = $this->argument('time');

        $this->notifier->sendPastDueReminder($days, $time);
    }
}
