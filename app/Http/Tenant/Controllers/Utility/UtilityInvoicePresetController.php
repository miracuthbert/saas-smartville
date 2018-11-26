<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Utilities\Models\UtilityInvoice;
use Smartville\Http\Tenant\Requests\UtilityInvoicePresetStoreRequest;

class UtilityInvoicePresetController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param UtilityInvoicePresetStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function setupInvoices(UtilityInvoicePresetStoreRequest $request)
    {
        if (Gate::denies('utilityInvoice.create', UtilityInvoice::class)) {
            return redirect()->route('tenant.dashboard');
        }

        session()->put('utility_generate_invoice', $request->only('utility_id', 'start_at', 'end_at', 'sent_at'));

        return redirect()->route('tenant.utilities.invoices.create')
            ->withInput([
                'start_at' => session('utility_generate_invoice.start_at'),
                'end_at' => session('utility_generate_invoice.end_at'),
                'send_at' => session('utility_generate_invoice.send_at'),
            ]);
    }
}
