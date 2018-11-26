<?php

namespace Smartville\Domain\Company\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Users\Models\User;

class ExistingUserTeamInvitation
{
    use Dispatchable, SerializesModels;

    /**
     * Invitation Company.
     *
     * @var Company
     */
    public $company;

    /**
     * The user invited.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param Company $company
     * @param User $user
     */
    public function __construct(Company $company, User $user)
    {
        $this->company = $company;
        $this->user = $user;
    }
}
