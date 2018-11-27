<?php

namespace Smartville\Domain\Company\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Smartville\App\Tenant\Models\Tenant;
use Smartville\Domain\Company\Mail\AdminWelcomeEmail;
use Smartville\Domain\Users\Models\User;

class CreateDefaultCompanyAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Tenant
     */
    protected $company;

    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param $company
     * @param $user
     */
    public function __construct(Tenant $company, User $user)
    {
        $this->company = $company;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $role = $this->company->roles()->where('name', 'Administrator')->first();

        $this->user->companyRoles()->syncWithoutDetaching($role->id);

        // send mail to user
        Mail::to($this->user)->send(new AdminWelcomeEmail($this->company, $this->user));
    }
}
