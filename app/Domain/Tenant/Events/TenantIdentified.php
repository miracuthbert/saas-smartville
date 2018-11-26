<?php

namespace Smartville\Domain\Tenant\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Smartville\App\Tenant\Models\Tenant;

class TenantIdentified
{
    use Dispatchable, SerializesModels;

    /**
     * @var Tenant
     */
    public $tenant;


    /**
     * Create a new event instance.
     *
     * @param Tenant $tenant
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
