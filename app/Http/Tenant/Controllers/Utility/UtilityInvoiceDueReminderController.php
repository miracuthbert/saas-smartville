<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Utilities\Models\UtilityInvoice;
use Smartville\Domain\Utilities\Notifications\UtilityInvoiceDueReminder;
use Smartville\Domain\Utilities\Notifications\UtilityInvoicePastDueReminder;

class UtilityInvoiceDueReminderController extends Controller
{
    /**
     * Send tenant a rent due reminder.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function sendReminder(Request $request, UtilityInvoice $utilityInvoice)
    {
        // check if cleared then redirect to show
        if ($utilityInvoice->cleared_at) {
            return redirect()->route('tenant.utilities.invoices.show', $utilityInvoice);
        }

        if (Carbon::now()->lt($utilityInvoice->due_at)) {   // send user utility invoice due reminder notification
            $utilityInvoice->user->notify(new UtilityInvoiceDueReminder($utilityInvoice));
        } else { // send user utility invoice past due reminder notification
            $utilityInvoice->user->notify(new UtilityInvoicePastDueReminder($utilityInvoice));
        }

        return back()->withSuccess(
            "{$utilityInvoice->utility->name} invoice payment due reminder for 
            {$utilityInvoice->formattedInvoiceMonth} sent to {$utilityInvoice->user->name}."
        );
    }
}
