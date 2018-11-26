<?php

namespace Smartville\Domain\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Smartville\App\Tenant\Models\Tenant;
use Smartville\Domain\Users\Models\User;

class CompanyCreated
{
    use Dispatchable, SerializesModels;
    
    /**
     * @var Tenant
     */
    public $tenant;
    
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param Tenant $tenant
     * @param User $user
     */
    public function __construct(Tenant $tenant, User $user)
    {
        $this->tenant = $tenant;
        $this->user = $user;
    }
}
