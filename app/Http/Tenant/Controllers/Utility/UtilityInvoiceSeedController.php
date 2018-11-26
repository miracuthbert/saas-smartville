<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Utilities\Jobs\CreateUtilityInvoice;
use Smartville\Domain\Utilities\Models\Utility;

class UtilityInvoiceSeedController extends Controller
{
    public function seed()
    {
        if (env('APP_ENV') !== 'local') {
            return;
        }

        $i = 1;

        $utility = Utility::forAllProperties()->where('billing_type', 'fixed')->first();

        $leases = Lease::with('property', 'user')
            ->whereHas('property.utilities', function ($query) use ($utility) {
                return $query->where('utility_id', $utility->id);
            })->active()->get();

        $date = now();

        $leases->each(function ($lease) use ($utility, $i, $date) {

            do {
                $month = $date->copy()->month($i);

                $start_at = $month->startOfMonth();
                $end_at = $month->copy()->endOfMonth();
                $sent_at = $month->copy()->subMonth()->endOfMonth()->day < $utility->billing_day ?
                    $month->copy()->subMonth()->endOfMonth() :
                    $month->copy()->subMonth()->day($utility->billing_day);
                $due_at = $month->copy()->addDays($utility->billing_due);

                // property readings
                $propertyReadings = [
                    'id' => $lease->property->id,
                    'previous' => null,
                    'current' => null,
                ];


                // dispatch job to create lease (rent) invoice
                dispatch(
                    new CreateUtilityInvoice($lease, $utility, $start_at, $end_at, $sent_at, $due_at, $propertyReadings)
                )->delay(now()->addSeconds(45));

                $i++;

            } while ($i <= $date->copy()->month);

        });

        return back()->withInfo('Invoices queued for seeding.');
    }
}
