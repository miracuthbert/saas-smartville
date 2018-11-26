<?php

namespace Smartville\Domain\Properties\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\UserInvitation;

class UnregistedUserTenantInvitation
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
     * Date tenant moves in.
     *
     * @var $movesIn
     */
    public $movesIn;

    /**
     * Date tenant moves out.
     *
     * @var $movesOut
     */
    public $movesOut;

    /**
     * Create a new event instance.
     *
     * @param Property $property
     * @param UserInvitation $invitation
     * @param $movesIn
     * @param $movesOut
     */
    public function __construct(Property $property, UserInvitation $invitation, $movesIn, $movesOut = null)
    {
        $this->property = $property;
        $this->invitation = $invitation;
        $this->movesIn = $movesIn;
        $this->movesOut = $movesOut;
    }
}
