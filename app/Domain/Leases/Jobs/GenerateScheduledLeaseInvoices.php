<?php

namespace Smartville\Domain\Leases\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Smartville\App\Settings\TenantRentSettings;
use Smartville\Domain\Company\Models\Company;

class GenerateScheduledLeaseInvoices implements ShouldQueue
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

        // get start day
        $startAt = now()->endOfMonth()->next()->startOfMonth();

        // get end day
        $endAt = now()->endOfMonth()->next()->endOfMonth();

        // get companies with active utilities; set to send invoices today
        $companies = Company::with('properties.currentLease.user')
            ->whereIn('uuid', $uuids)
            ->whereHas('properties', function (Builder $builder) use ($day) {
                return $builder->whereHas('currentLease.user')
                    ->occupied();
            })->get();

        // loop through companies
        $companies->each(function ($company) use ($settings, $endAt, $startAt) {
            $properties = $company->properties()->whereHas('currentLease.user')->get();

            // get send date
            $sendAt = now();

            $companySettings = $settings[$company->uuid]['due-day'] ?? TenantRentSettings::$defaults['due-day'];

            // get due date
            $dueAt = now()->addDays($due = ($companySettings));

            // loop through properties
            $properties->each(function ($property) use ($sendAt, $dueAt, $endAt, $startAt) {

                // dispatch job to create lease (rent) invoice
                dispatch(
                    new CreateLeaseInvoice(
                        $property->currentLease, $startAt, $endAt, $sendAt, $dueAt
                    )
                )->delay(now()->addSeconds(45));
            });
        });
    }
}
