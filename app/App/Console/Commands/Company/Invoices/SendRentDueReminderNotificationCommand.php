<?php

namespace Smartville\App\Console\Commands\Company\invoices;

use Illuminate\Console\Command;
use Smartville\App\Services\RentInvoiceNotifier;

class SendRentDueReminderNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rent:due {days=3} {time?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send rent invoice due reminder notification to users';

    /**
     * The Rent Invoice Notifier Service.
     *
     * @var RentInvoiceNotifier
     */
    protected $notifier;

    /**
     * Create a new command instance.
     *
     * @param RentInvoiceNotifier $notifier
     */
    public function __construct(RentInvoiceNotifier $notifier)
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

        $this->notifier->sendDueReminder($days, $time);
    }
}
