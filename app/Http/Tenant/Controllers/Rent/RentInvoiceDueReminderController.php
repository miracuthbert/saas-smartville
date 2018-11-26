<?php

namespace Smartville\Http\Tenant\Controllers\Rent;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Smartville\Domain\Leases\Notifications\RentInvoiceDueReminder;
use Smartville\Domain\Leases\Notifications\RentInvoicePastDueReminder;

class RentInvoiceDueReminderController extends Controller
{
    /**
     * Send tenant a rent due reminder.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function sendReminder(Request $request, LeaseInvoice $leaseInvoice)
    {
        // check if cleared then redirect to show
        if ($leaseInvoice->cleared_at) {
            return redirect()->route('tenant.rent.invoices.show', $leaseInvoice);
        }

        if (Carbon::now()->lt($leaseInvoice->due_at)) { // send user rent invoice reminder notification
            $leaseInvoice->user->notify(
                (new RentInvoiceDueReminder($leaseInvoice))->delay(now()->addSeconds(30))
            );
        } else {    // send user rent invoice past reminder notification
            $leaseInvoice->user->notify(
                (new RentInvoicePastDueReminder($leaseInvoice))->delay(now()->addSeconds(30))
            );
        }

        return back()->withSuccess("Rent invoice reminder sent successfully.");
    }
}
