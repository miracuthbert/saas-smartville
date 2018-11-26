<?php

namespace Smartville\Http\Tenant\Controllers\Rent;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Leases\Jobs\CreateBulkLeaseInvoices;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Leases\Notifications\NewRentInvoice;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Http\Tenant\Requests\LeaseInvoiceStoreRequest;
use Smartville\Http\Tenant\Requests\LeaseInvoiceUpdateRequest;

class RentInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Gate::denies('leaseInvoice.browse', LeaseInvoice::class)) {
            return redirect()->route('tenant.dashboard');
        }

        $invoices = $request->tenant()->rentInvoices()
            ->filter($request)
            ->with('property', 'user', 'payments')
            ->paginate();

        return view('tenant.rent.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('leaseInvoice.create', LeaseInvoice::class)) {
            return redirect()->route('tenant.dashboard');
        }

        $properties = Property::has('currentLease.user')->with('currentLease.user')->occupied()->get();

        return view('tenant.rent.create', compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeaseInvoiceStoreRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(LeaseInvoiceStoreRequest $request)
    {
        if (Gate::denies('leaseInvoice.create', LeaseInvoice::class)) {
            return redirect()->route('tenant.dashboard');
        }

        // redirect back if no properties selected
        if (!$request->has('properties')) {
            return back()->withError("Please select at least one property")
                ->withInput();
        }

        // property ids
        $propertyIds = collect(array_values($request->properties))->pluck('id');

        // dispatch job to create bulk lease (rent) invoices
        dispatch(new CreateBulkLeaseInvoices(
            $propertyIds, $request->start_at, $request->end_at, $request->sent_at, $request->due_at)
        )->delay(now()->addMinutes(3));

//        // find properties
//        $properties = Property::with('currentLease.user')->findMany($propertyIds);
//
//        // get diff in month
//        $diffInMonth = $this->getInvoiceDiffInMonth($request->end_at, $request->start_at);
//
//        // preset rent invoices
//        $invoices = $properties->map(function ($property) use ($diffInMonth, $request) {
//            $invoice = new LeaseInvoice();
//            $invoice->fill($request->only('start_at', 'end_at', 'sent_at', 'due_at'));
//            $invoice->setAttribute('currency', $property->currency);
//            $invoice->setAttribute('amount', ($property->price * $diffInMonth));
//            $invoice->property()->associate($property);
//            $invoice->lease()->associate($property->currentLease);
//            $invoice->user()->associate($property->currentLease->user);
//
//            return $invoice;
//        });
//
//        // todo: refactor this to use CreateLeaseInvoice job
//        // save invoices
//        $count = $invoices->each(function ($invoice) {
//            $invoice->save();
//            $invoice->user->notify(new NewRentInvoice($invoice));
//        })->count();
//
//        $invCount = $count . ' rent ' . str_plural('invoice', $count);

//        cache()->forget('rentInvoices');

        return redirect()->route('tenant.rent.invoices.index')
            ->withSuccess("Invoices will be generated shortly and the respective tenants will be notified.")
            ->withInfo("You can make changes to individual invoices once they are generated.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(LeaseInvoice $leaseInvoice)
    {
        if (Gate::denies('leaseInvoice.view', $leaseInvoice)) {
            return redirect()->route('tenant.dashboard');
        }

        $leaseInvoice->load([
            'property',
            'lease',
            'user',
            'payments' => function ($query) {
                return $query->orderByDesc('paid_at');
            },
            'payments.invoice',
            'payments.admin'
        ]);

        return view('tenant.rent.show', compact('leaseInvoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaseInvoice $leaseInvoice)
    {
        if (Gate::denies('leaseInvoice.update', $leaseInvoice)) {
            return redirect()->route('tenant.dashboard');
        }

        if ($leaseInvoice->cleared_at) {
            return redirect()->route('tenant.rent.invoices.show', $leaseInvoice);
        }

        if (Carbon::now()->gt($leaseInvoice->sent_at)) {
            return redirect()->route('tenant.rent.invoices.clear', $leaseInvoice);
        }

        $leaseInvoice->load('property', 'lease', 'user');

        return view('tenant.rent.edit', compact('leaseInvoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LeaseInvoiceUpdateRequest $request
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(LeaseInvoiceUpdateRequest $request, LeaseInvoice $leaseInvoice)
    {
        if (Gate::denies('leaseInvoice.update', $leaseInvoice)) {
            return redirect()->route('tenant.dashboard');
        }

        $property = $leaseInvoice->property;

        // get diff in month
        $diffInMonth = $this->getInvoiceDiffInMonth($request->end_at, $request->start_at);
        $send = $request->send_invoice;

        $leaseInvoice->fill($request->only('start_at', 'end_at', 'sent_at', 'due_at'));
        $leaseInvoice->initialAmount = ($property->price * $diffInMonth);

        // set sent_at date if invoice is ready
        if ($send) {
            $leaseInvoice->sent_at = Carbon::now();
        }

        $leaseInvoice->save();

        cache()->forget('rentInvoices');

        if ($send) {    // redirect to invoice show

            // optional: call (RentInvoiceGenerated) event to notify admin
            // send user new invoice notification
            $leaseInvoice->user->notify(new NewRentInvoice($leaseInvoice));

            return redirect()->route('tenant.rent.invoices.show', $leaseInvoice)
                ->withSuccess("Invoice successfully updated and sent to tenant.");
        }

        return back()->withSuccess("Invoice successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Leases\Models\LeaseInvoice $leaseInvoice
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(LeaseInvoice $leaseInvoice)
    {
        if (Gate::denies('leaseInvoice.delete', $leaseInvoice)) {
            return redirect()->route('tenant.dashboard');
        }

        if ($leaseInvoice->cleared_at) {
            return back()->withWarning("You cannot delete a cleared invoice.");
        }

        if ($leaseInvoice->paymentsTotal() > 0) {
            return back()->withWarning("You cannot delete an invoice that has initial payments made.");
        }

        try {
            $leaseInvoice->delete();
        } catch (\Exception $e) {
            return back()
                ->withError("Some error occured when trying to delete invoice no: {$leaseInvoice->hash_id}.");
        }

        cache()->forget('rentInvoices');

        return back()->withSuccess(
            "Invoice #: {$leaseInvoice->hash_id} for {$leaseInvoice->invoiceMonth} successfully deleted."
        );
    }

    /**
     * Calculate and return the difference in month between rent invoice dates.
     *
     * @param $end_at
     * @param $start_at
     * @return int
     */
    protected function getInvoiceDiffInMonth($end_at, $start_at)
    {
        $diffInMonth = Carbon::parse($end_at)->diffInMonths(Carbon::parse($start_at));

        return $diffInMonth <= 0 ? 1 : $diffInMonth;
    }
}
