<?php

namespace Smartville\Domain\Company\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Smartville\Domain\Company\Models\Company;

class CompanyWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Instance of company.
     *
     * @var Company
     */
    public $company;

    /**
     * Create a new message instance.
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome')->markdown('emails.company.welcome');
    }
}
