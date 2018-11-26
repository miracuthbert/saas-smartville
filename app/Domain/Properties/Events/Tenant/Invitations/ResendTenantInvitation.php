<?php

namespace Smartville\Domain\Properties\Events\Tenant\Invitations;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\UserInvitation;

class ResendTenantInvitation
{
    use Dispatchable, SerializesModels;

    /**
     * Tenant property.
     *
     * @var Property
     */
    public $property;

    /**
     * User invitation.
     *
     * @var UserInvitation
     */
    public $invitation;

    /**
     * Create a new event instance.
     *
     * @param Property $property
     * @param UserInvitation $invitation
     */
    public function __construct(Property $property, UserInvitation $invitation)
    {
        $this->property = $property;
        $this->invitation = $invitation;
    }
}
