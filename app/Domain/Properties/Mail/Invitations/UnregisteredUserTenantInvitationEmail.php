<?php

namespace Smartville\Domain\Properties\Mail\Invitations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\UserInvitation;

class UnregisteredUserTenantInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Tenant property.
     *
     * @var Property
     */
    public $property;

    /**
     * User name.
     *
     * @var $name
     */
    public $name;

    /**
     * User invitation.
     *
     * @var UserInvitation
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @param Property $property
     * @param UserInvitation $invitation
     */
    public function __construct(Property $property, UserInvitation $invitation)
    {
        $this->property = $property;
        $this->name = $invitation->name;
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Tenant Access")
            ->markdown('tenant.emails.properties.invitations.tenant.unregistered.invite');
    }
}
