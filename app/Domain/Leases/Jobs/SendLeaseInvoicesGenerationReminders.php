<?php

namespace Smartville\Domain\Leases\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Smartville\App\Settings\TenantRentSettings;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Leases\Notifications\RentInvoicesGenerationReminder;
use Smartville\Domain\Leases\Notifications\RentInvoicesGenerationScheduledReminder;

class SendLeaseInvoicesGenerationReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param TenantRentSettings $settings
     * @return void
     */
    public function handle(TenantRentSettings $settings)
    {
        // get day based on date
        $day = now()->day;

        // get query operator based on day of month
        $operator = ($day == now()->endOfMonth()->day) ? '>=' : '=';

        $uuids = collect($settings->all())->where('auto-generate', true)
            ->where('billing-day', $operator, $day)
            ->keys()
            ->all();

        // get companies with active utilities; set to send invoices today
        $companies = Company::whereIn('uuid', $uuids)
            ->whereHas('properties', function (Builder $builder) use ($day) {
                return $builder->whereHas('currentLease.user')->occupied();
            })->get();

        // loop through companies
        $companies->each(function ($company) use ($settings) {
            if ($settings[$company->uuid]['auto-generate'] == true) {
                // send message if auto generated
                Notification::send($company, new RentInvoicesGenerationScheduledReminder());
            } else {
                // send reminder for creating invoices
                Notification::send($company, new RentInvoicesGenerationReminder());
            }
        });
    }
}
