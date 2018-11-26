<?php

namespace Smartville\Domain\Properties\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\User;

class ExistingUserTenantInvitation
{
    use Dispatchable, SerializesModels;

    /**
     * Tenant property.
     *
     * @var Property
     */
    public $property;

    /**
     * Invitation user.
     *
     * @var User
     */
    public $user;

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
     * @param User $user
     * @param $movesIn
     * @param null $movesOut
     */
    public function __construct(Property $property, User $user, $movesIn, $movesOut = null)
    {
        $this->property = $property;
        $this->user = $user;
        $this->movesIn = $movesIn;
        $this->movesOut = $movesOut;
    }
}
