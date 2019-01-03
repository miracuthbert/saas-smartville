<?php

namespace Smartville\Http\Tenant\Controllers\Account;

use Smartville\Domain\Company\Models\CompanyPaymentMethod;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Tenant\Requests\PaymentMethodStoreRequest;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $methods = CompanyPaymentMethod::latest()->paginate();

        return view('tenant.account.payments.methods.index', compact('methods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tenant.account.payments.methods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PaymentMethodStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentMethodStoreRequest $request)
    {
        $companyPaymentMethod = new CompanyPaymentMethod();
        $companyPaymentMethod->fill($request->only('name', 'details', 'usable'));
        $companyPaymentMethod->save();

        return redirect()->route('tenant.account.payments.methods.index')
            ->withSuccess('Payment method added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Company\Models\CompanyPaymentMethod  $companyPaymentMethod
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyPaymentMethod $companyPaymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Company\Models\CompanyPaymentMethod  $companyPaymentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyPaymentMethod $companyPaymentMethod)
    {
        return view('tenant.account.payments.methods.edit', compact('companyPaymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PaymentMethodStoreRequest $request
     * @param  \Smartville\Domain\Company\Models\CompanyPaymentMethod $companyPaymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentMethodStoreRequest $request, CompanyPaymentMethod $companyPaymentMethod)
    {
        $companyPaymentMethod->fill($request->only('name', 'details', 'usable'));
        $companyPaymentMethod->save();

        return redirect()->route('tenant.account.payments.methods.index')
            ->withSuccess("`{$companyPaymentMethod->name}` payment method updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Company\Models\CompanyPaymentMethod $companyPaymentMethod
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(CompanyPaymentMethod $companyPaymentMethod)
    {
        try {
            $companyPaymentMethod->delete();
        } catch (\Exception $e) {
            return back()->withError("{$companyPaymentMethod->name} could not be removed from company's payment methods.");
        }

        return back()->withSuccess("{$companyPaymentMethod->name} removed from company's payment methods.");
    }
}
