<?php

namespace Smartville\Domain\Utilities\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Smartville\App\Settings\TenantUtilitySettings;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Utilities\Models\Utility;

class GenerateScheduledUtilityInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @param TenantUtilitySettings $settings
     * @return void
     */
    public function handle(TenantUtilitySettings $settings)
    {
        $uuids = collect($settings->all())->where('auto-generate', true)->keys()->all();

        // loop through the billing intervals
        foreach (Utility::$billingIntervals as $key => $interval) {

            // get day based on interval
            $day = $this->getDayFromInterval($key);

            // get query operator based on day and billing interval
            $operator = $this->getOperatorBasedOnDayAndInterval($day, $key);

            // get start day
            $startAt = $this->getStartDayFromInterval($key);

            // get end day
            $endAt = $this->getEndDayFromIntervalAndStartDay($key, $startAt);

            // get companies with active utilities; set to send invoices today
            $companies = Company::with('utilities.properties.currentLease.user')
                ->whereIn('uuid', $uuids)
                ->whereHas('utilities', function (Builder $builder) use ($operator, $day, $key) {
                    return $builder->where('billing_interval', '=', $key)
                        ->where('billing_day', $operator, $day)
                        ->where('billing_type', '=', 'fixed')
                        ->where('usable', true)
                        ->whereHas('properties.currentLease.user');
                })->get();

            // loop through the companies
            $companies->each(function ($company) use ($endAt, $startAt, $day) {
                // get company's utilities
                $utilities = $company->utilities->whereStrict('billing_day', $day);

                // loop through utilities
                $utilities->each(function ($utility) use ($endAt, $startAt, $company) {

                    // get utilities properties
                    $properties = $utility->properties;

                    // loop through properties
                    $properties->each(function ($property) use ($utility, $endAt, $startAt) {
                        // get property's current lease
                        $lease = $property->currentLease;

                        // get user from lease
                        $user = optional($lease)->user;

                        // get send day
                        $sendAt = now();

                        // get due date
                        $dueAt = now()->addDays($utility->billing_due);

                        if (!$user) {
                            return;
                        }

                        // dispatch job to create utility invoice
                        dispatch(new CreateUtilityInvoice(
                            $lease, $utility, $startAt, $endAt, $sendAt, $dueAt, []
                        ))->delay(now()->addSeconds(45));

                        // todo: create a service to handle invoice creation
                    });
                });
            });
        }
    }

    /**
     * Get billing day based on interval.
     *
     * @param $interval
     * @return int
     */
    private function getDayFromInterval($interval)
    {
        $type = Utility::$formattedBillingIntervals[$interval];

        switch ($type) {
            case 'week':
                $day = now()->dayOfWeek + 1;
                return $day;
            case 'month':
                $day = Carbon::parse()->month(now()->month)->day;
                return $day;
            default:
                $day = Carbon::parse()->month(now()->month)->day;
                return $day;
        }
    }

    /**
     * Get billing start date based on interval.
     *
     * @param $interval
     * @return static
     */
    private function getStartDayFromInterval($interval)
    {   // todo: find out if utility is prepaid or postpaid
        $type = Utility::$formattedBillingIntervals[$interval];

        switch ($type) {
            case 'week':
                $day = now()->startOfWeek();
                return $day;
            case 'month':
                $day = now()->startOfMonth();
                return $day;
            default:
                $day = now()->startOfMonth();
                return $day;
        }
    }

    /**
     * Get billing end date based on interval and start date.
     *
     * @param $interval
     * @param $startAt
     * @return Carbon
     */
    private function getEndDayFromIntervalAndStartDay($interval, $startAt)
    {
        $type = Utility::$formattedBillingIntervals[$interval];

        switch ($type) {
            case 'week':
                $day = Carbon::parse($startAt)->endOfWeek();
                return $day;
            case 'month':
                $day = Carbon::parse($startAt)->endOfMonth();
                return $day;
            default:
                $day = Carbon::parse($startAt)->endOfMonth();
                return $day;
        }
    }

    /**
     * Get query operator based on day and interval.
     *
     * @param $day
     * @param $interval
     * @return string
     */
    private function getOperatorBasedOnDayAndInterval($day, $interval)
    {
        $type = Utility::$formattedBillingIntervals[$interval];

        switch ($type) {
            case 'week':
                return ($day == now()->endOfWeek()->day) ? '>=' : '=';
            case 'month':
                return ($day == now()->endOfMonth()->day) ? '>=' : '=';
            default:
                return '=';
        }
    }
}
