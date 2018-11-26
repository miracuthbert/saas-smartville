<?php

namespace Smartville\Domain\Utilities\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Utilities\Models\Utility;
use Smartville\Domain\Utilities\Models\UtilityInvoice;
use Smartville\Domain\Utilities\Notifications\NewUtilityInvoice;

class CreateUtilityInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Lease
     */
    protected $lease;

    /**
     * @var Utility
     */
    protected $utility;

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
     * @var $propertyReadings
     */
    protected $propertyReadings;

    /**
     * @var $user
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param Lease $lease
     * @param Utility $utility
     * @param $start_at
     * @param $end_at
     * @param $sent_at
     * @param $due_at
     * @param $propertyReadings
     */
    public function __construct(Lease $lease, Utility $utility, $start_at, $end_at, $sent_at, $due_at, $propertyReadings)
    {
        $this->lease = $lease;
        $this->utility = $utility;
        $this->start_at = $start_at;
        $this->end_at = $end_at;
        $this->sent_at = $sent_at;
        $this->due_at = $due_at;
        $this->propertyReadings = $propertyReadings;
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

        $company = $property->company;

        config()->set('app.name', $company->name);
        config()->set('app.short_name', $company->short_name);

        $invoice = UtilityInvoice::firstOrNew([
            'start_at' => Carbon::parse($this->start_at)->toDateString(),
            'end_at' => Carbon::parse($this->end_at)->toDateString(),
            'utility_id' => $this->utility->id,
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
        $invoice->currency = $this->utility->currency;

        $invoice->setAttribute('price', $this->utility->priceAmount);

        // set utility readings for varied
        if ($this->utility->billing_type == 'varied') {
            $invoice->previous = $this->propertyReadings['previous'];
            $invoice->current = $this->propertyReadings['current'];
            $invoice->units = $this->utility->billing_unit;
        }

        $invoice->utility()->associate($this->utility);
        $invoice->property()->associate($property);
        $invoice->lease()->associate($this->lease);
        $invoice->user()->associate($this->user);
        $invoice->save();

        $this->user->notify(
            (new NewUtilityInvoice($invoice))->delay(now()->addMinutes($this->notifyAt()))
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
}
