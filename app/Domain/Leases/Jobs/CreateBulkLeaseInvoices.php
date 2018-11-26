<?php

namespace Smartville\Domain\Leases\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Smartville\Domain\Properties\Models\Property;

class CreateBulkLeaseInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $ids
     */
    protected $ids;

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
     * @param $ids
     * @param $start_at
     * @param $end_at
     * @param $sent_at
     * @param $due_at
     */
    public function __construct($ids, $start_at, $end_at, $sent_at, $due_at)
    {
        $this->ids = $ids;
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
        // fetch properties
        $properties = Property::with('currentLease.user')->findMany($this->ids);

        // loop through properties
        $properties->each(function ($property) {

            // get lease
            $lease = $property->currentLease;

            // dispatch job to create lease (rent) invoice
            dispatch(new CreateLeaseInvoice($lease, $this->start_at, $this->end_at, $this->sent_at, $this->due_at))
                ->delay(now()->addSeconds(45));
        });
    }
}
