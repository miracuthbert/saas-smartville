<?php

namespace Smartville\App\Console\Commands\Company\Invoices;

use Illuminate\Console\Command;
use Smartville\App\Services\RentInvoiceNotifier;

class SendNewRentInvoiceNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rent:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send new rent invoice notification to users';

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
        $this->notifier->sendNew();
    }
}
