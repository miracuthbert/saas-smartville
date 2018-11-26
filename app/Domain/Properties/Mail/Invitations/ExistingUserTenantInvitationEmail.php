<?php

namespace Smartville\Domain\Properties\Mail\Invitations;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\User;

class ExistingUserTenantInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

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
     * Tenant lease.
     *
     * @var \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public $lease;

    /**
     * Create a new message instance.
     *
     * @param Property $property
     * @param User $user
     */
    public function __construct(Property $property, User $user)
    {
        $this->property = $property;
        $this->user = $user;
        $this->lease = $property->leases()->where('user_id', $user->id)
            ->whereNull('moved_out_at')->latest()->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Tenant Access")
            ->markdown('tenant.emails.properties.invitations.tenant.existing.invite');
    }
}
