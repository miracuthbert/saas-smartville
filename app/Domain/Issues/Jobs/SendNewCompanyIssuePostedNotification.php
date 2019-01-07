<?php

namespace Smartville\Domain\Issues\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Issues\Models\Issue;
use Smartville\Domain\Issues\Notifications\NewCompanyIssuePosted;

class SendNewCompanyIssuePostedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Issue
     */
    protected $issue;

    /**
     * @var Company
     */
    protected $company;

    /**
     * Create a new job instance.
     *
     * @param Issue $issue
     * @param Company $company
     */
    public function __construct(Issue $issue, Company $company)
    {
        $this->issue = $issue;
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // find company user's who can view issues
        $users = $this->company->users()->whereHas('companyRoles', function ($query) {
            return $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', Carbon::now());
        })->whereHas('companyRoles.permissions', function ($query) {
            return $query->where('name', 'browse company issues');
        })->get();

        // send bulk notification to user's
        Notification::send($users, new NewCompanyIssuePosted($this->issue, $this->company));
    }
}
