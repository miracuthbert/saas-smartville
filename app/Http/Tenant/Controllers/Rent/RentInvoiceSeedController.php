<?php

namespace Smartville\Http\Tenant\Controllers\Rent;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Jobs\CreateLeaseInvoice;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Leases\Models\LeaseInvoice;

class RentInvoiceSeedController extends Controller
{
    public function seed()
    {
        $i = 1;

        $leases = Lease::with('property', 'user')->active()->get();

        $date = now();

        $leases->each(function ($lease) use ($i, $date) {

            do {
                $month = $date->copy()->month($i);

                $start_at = $month->startOfMonth();
                $end_at = $month->copy()->endOfMonth();
                $sent_at = $month->copy()->subMonth()->endOfMonth();
                $due_at = $month->copy()->addDays(7);


                // dispatch job to create lease (rent) invoice
                dispatch(new CreateLeaseInvoice($lease, $start_at, $end_at, $sent_at, $due_at))
                    ->delay(now()->addSeconds(45));

                $i++;

            } while ($i <= $date->copy()->month);

        });

        return back()->withInfo('Invoices queued for seeding.');
    }
}
