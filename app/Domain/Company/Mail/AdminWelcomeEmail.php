<?php

namespace Smartville\Domain\Company\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Users\Models\User;

class AdminWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Instance of company.
     *
     * @var Company
     */
    public $company;

    /**
     * Instance of user.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @param Company $company
     * @param User $user
     */
    public function __construct(Company $company, User $user)
    {
        $this->company = $company;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Administrator Role Privileges')
            ->markdown('tenant.emails.company.team.invitations.user.admin.welcome');
    }
}
