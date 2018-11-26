<?php

namespace Smartville\Domain\Leases\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Smartville\Domain\Leases\Notifications\NewRentInvoice;

class CreateLeaseInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Lease
     */
    protected $lease;

    /**
     * @var $start_at
     */
    protected $start_at;

    /**
     * @var $end_at
     */
    protected $end_at;

    /**
     * @var $sent_at
     */
    protected $sent_at;

    /**
     * @var $due_at
     */
    protected $due_at;

    /**
     * @var $user
     */
    protected $user;

    /**
     * Create a new job instance.
     * @param Lease $lease
     * @param $start_at
     * @param $end_at
     * @param $sent_at
     * @param $due_at
     */
    public function __construct(Lease $lease, $start_at, $end_at, $sent_at, $due_at)
    {
        $this->lease = $lease;
        $this->start_at = $start_at;
        $this->end_at = $end_at;
        $this->sent_at = $sent_at;
        $this->due_at = $due_at;
        $this->user = $lease->user;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $property = $this->lease->property;
        $interval = $this->getInvoiceInterval();
        $company = $property->company;

        config()->set('app.name', $company->name);
        config()->set('app.short_name', $company->short_name);

        $invoice = LeaseInvoice::firstOrNew([
            'start_at' => Carbon::parse($this->start_at)->toDateString(),
            'end_at' => Carbon::parse($this->end_at)->toDateString(),
            'lease_id' => $this->lease->id,
            'user_id' => $this->user->id,
        ]);

        $hasId = isset($invoice->id);

        if ($hasId === true) {
            return;
        }

        $invoice->start_at = $this->start_at;
        $invoice->end_at = $this->end_at;
        $invoice->sent_at = $this->sent_at;
        $invoice->due_at = $this->due_at;
        $invoice->currency = $property->currency;
        $invoice->setAttribute('amount', ($property->priceAmount * $interval));
        $invoice->property()->associate($property);
        $invoice->lease()->associate($this->lease);
        $invoice->user()->associate($this->user);
        $invoice->save();

        $this->user->notify(
            (new NewRentInvoice($invoice))->delay(now()->addMinutes($this->notifyAt()))
        );
    }

    /**
     * Calculate and return the difference in minutes to send invoice notification.
     *
     * @return int
     */
    protected function notifyAt()
    {
        return Carbon::now()->diffInMinutes(Carbon::parse($this->sent_at));
    }

    /**
     * Calculate and return the difference in month between rent invoice dates.
     *
     * @return int
     */
    protected function getInvoiceInterval()
    {
        $diffInMonth = Carbon::parse($this->end_at)->diffInMonths(Carbon::parse($this->start_at));

        return $diffInMonth <= 0 ? 1 : $diffInMonth;
    }
}
