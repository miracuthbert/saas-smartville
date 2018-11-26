<?php

namespace Smartville\Domain\Leases\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Smartville\Domain\Leases\Models\LeaseInvoice;

class RentInvoiceGenerated
{
    use Dispatchable, SerializesModels;

    /**
     * Invoice being sent.
     *
     * @var LeaseInvoice
     */
    public $leaseInvoice;

    /**
     * Create a new event instance.
     *
     * @param LeaseInvoice $leaseInvoice
     */
    public function __construct(LeaseInvoice $leaseInvoice)
    {
        $this->leaseInvoice = $leaseInvoice;
    }
}
