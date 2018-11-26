<?php

namespace Smartville\Domain\Utilities\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Utilities\Models\Utility;

class CreateBulkUtilityInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $propertiesReadings
     */
    protected $propertiesReadings;

    /**
     * @var $utilityId
     */
    protected $utilityId;

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
     * Create a new job instance.
     *
     * @param $properties
     * @param $utilityId
     * @param $start_at
     * @param $end_at
     * @param $sent_at
     * @param $due_at
     */
    public function __construct($properties, $utilityId, $start_at, $end_at, $sent_at, $due_at)
    {
        $this->propertiesReadings = $properties;
        $this->utilityId = $utilityId;
        $this->start_at = $start_at;
        $this->end_at = $end_at;
        $this->sent_at = $sent_at;
        $this->due_at = $due_at;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // find utility
        $utility = Utility::find($this->utilityId);

        foreach ($this->propertiesReadings as $propertyReadings) {
            $property = Property::with('currentLease.user')->find($propertyReadings['id']);

            // get lease
            $lease = $property->currentLease;

            // dispatch job to create utility invoice
            dispatch(new CreateUtilityInvoice(
                $lease, $utility, $this->start_at, $this->end_at, $this->sent_at, $this->due_at, $propertyReadings
            ))->delay(now()->addSeconds(45));
        }
    }
}
