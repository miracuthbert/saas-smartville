<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Utilities\Jobs\CreateBulkUtilityInvoices;
use Smartville\Domain\Utilities\Models\Utility;
use Smartville\Domain\Utilities\Models\UtilityInvoice;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Tenant\Requests\UtilityInvoiceStoreRequest;
use Smartville\Http\Tenant\Requests\UtilityInvoiceUpdateRequest;

class UtilityInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Gate::denies('utilityInvoice.browse', UtilityInvoice::class)) {
            return redirect()->route('tenant.dashboard');
        }

        $invoices = $request->tenant()->utilityInvoices()
            ->filter($request)
            ->with(['utility.company', 'property', 'user', 'payments.invoice'])
            ->paginate();

        return view('tenant.utilities.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('utilityInvoice.create', UtilityInvoice::class)) {
            return redirect()->route('tenant.dashboard');
        }

        if (!session()->has('utility_generate_invoice')) {
            return view('tenant.utilities.invoices.start');
        }

        $sent_at = session('utility_generate_invoice.sent_at');

        // find utility
        $utility = Utility::find(session('utility_generate_invoice.utility_id'));

        // find properties
        $properties = Property::whereHas('utilities', function ($query) use ($utility) {
            return $query->where('utility_id', $utility->id);
        })->has('currentLease.user')->with('currentLease.user')->get();

        return view('tenant.utilities.invoices.create', [
            'utility' => $utility,
            'properties' => $properties,
            'start_at' => session('utility_generate_invoice.start_at'),
            'end_at' => session('utility_generate_invoice.end_at'),
            'sent_at' => $sent_at,
            'due_at' => Carbon::parse($sent_at)->addDays($utility->billing_due),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UtilityInvoiceStoreRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(UtilityInvoiceStoreRequest $request)
    {
        if (Gate::denies('utilityInvoice.create', UtilityInvoice::class)) {
            return redirect()->route('tenant.dashboard');
        }

        if ($request->cancel) {
            session()->forget('utility_generate_invoice');

            return redirect()->route('tenant.utilities.invoices.create')
                ->withInfo('Utility invoices generation cancelled.');
        }

        $timezone = $request->tenant()->timezone;

        $delay = now($timezone)->addMinutes(
            $minutes = now($timezone)->diffInMinutes(
                Carbon::parse($request->sent_at)
            )
        );

        // dispatch job to create bulk lease (rent) invoices
        dispatch(new CreateBulkUtilityInvoices(
            $request->properties,
            $request->utility_id,
            $request->start_at,
            $request->end_at,
            $request->sent_at,
            $request->due_at
        ))->delay($delay);

        // find utility
        $utility = Utility::find($request->utility_id);

//        // property ids
//        $propertyIds = collect(array_values($request->properties))->pluck('id');
//
//        // find properties
//        $properties = Property::with('currentLease.user')->findMany($propertyIds);
//
//        // preset utility invoices
//        $invoices = $properties->map(function ($property) use ($utility, $request) {
//            $invoice = new UtilityInvoice();
//            $invoice->fill($request->only('start_at', 'end_at', 'sent_at', 'due_at'));
//
//            // set attributes
//            $invoice->setAttribute('currency', $utility->currency);
//            $invoice->setAttribute('price', $utility->price);
//
//            // set utility readings for varied
//            if ($utility->billing_type == 'varied') {
//                $reqProperty = collect(array_values($request->properties))->firstWhere('id', $property->id);
//
//                $invoice->setAttribute('previous', $reqProperty['previous']);
//                $invoice->setAttribute('current', $reqProperty['current']);
//                $invoice->setAttribute('units', $utility->billing_unit);
//            }
//
//            $invoice->property()->associate($property);
//            $invoice->lease()->associate($property->currentLease);
//            $invoice->user()->associate($property->currentLease->user);
//
//            return $invoice;
//        });
//
//        // save invoices
//        $count = ($sentInvoices = $utility->invoices()->saveMany($invoices))->count();
//
//        // send notifications
//        if (Carbon::now()->isSameDay(Carbon::parse($request->sent_at))) {
//            // send notifications
//        }
//
//        $invCount = $count . ' ' . str_plural('invoice', $count);

        // remove utility from session
        session()->forget('utility_generate_invoice');

//        cache()->forget('utilityInvoices');

        return redirect()->route('tenant.utilities.invoices.index')
            ->withSuccess("Invoices for {$utility->name} utility will be generated shortly.")
            ->withInfo("Tenants will be notified accordingly.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityInvoice.view', $utilityInvoice)) {
            return redirect()->route('tenant.dashboard');
        }

        $utilityInvoice->load([
            'utility',
            'property',
            'user',
            'payments' => function ($query) {
                return $query->orderByDesc('paid_at');
            },
            'payments.invoice',
            'payments.admin'
        ]);

        return view('tenant.utilities.invoices.show', compact('utilityInvoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityInvoice.update', $utilityInvoice)) {
            return redirect()->route('tenant.dashboard');
        }

        if (Carbon::now()->gt($utilityInvoice->sent_at)) {
            return redirect()->route('tenant.utilities.invoices.show', $utilityInvoice);
        }

        $utilityInvoice->load('utility', 'property', 'lease', 'user');

        return view('tenant.utilities.invoices.edit', compact('utilityInvoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UtilityInvoiceUpdateRequest $request
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(UtilityInvoiceUpdateRequest $request, UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityInvoice.update', $utilityInvoice)) {
            return redirect()->route('tenant.dashboard');
        }

        $utilityInvoice->fill($request->only('sent_at'));

        // set utility readings for varied
        if ($utilityInvoice->utility->billing_type == 'varied') {
            $utilityInvoice->previous = $request->previous;
            $utilityInvoice->current = $request->current;
        }

        // save
        $utilityInvoice->save();

        // optional: notify tenant if already sent

        cache()->forget('utilityInvoices');

        return back()->withSuccess("Invoice updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Utilities\Models\UtilityInvoice $utilityInvoice
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(UtilityInvoice $utilityInvoice)
    {
        if (Gate::denies('utilityInvoice.delete', $utilityInvoice)) {
            return redirect()->route('tenant.dashboard');
        }

        if ($utilityInvoice->cleared_at) {
            return back()->withWarning("You cannot delete a cleared invoice.");
        }

        if (round($utilityInvoice->paymentsTotal(), 2) > 0) {
            return back()->withWarning("You cannot delete an invoice that has initial payments made.");
        }

        try {
            $utilityInvoice->delete();
        } catch (\Exception $e) {
            return back()->withSuccess("Some error occurred when trying to invoice. Please try again!");
        }

        cache()->forget('utilityInvoices');

        return back()->withSuccess(
            "{$utilityInvoice->user->first_name}'s 
            Invoice #{$utilityInvoice->identifier} for 
            {$utilityInvoice->invoiceMonth} {$utilityInvoice->utility->name} deleted."
        );
    }
}
