<?php

namespace Smartville\Http\Tenant\Controllers\Account;

use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Tenant\Requests\CompanyStoreRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('update company account')) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.account.profile.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CompanyStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyStoreRequest $request)
    {
        if (Gate::denies('update company account')) {
            return redirect()->route('tenant.dashboard');
        }

        $company = $request->tenant();
        $company->fill($request->all());
        $company->save();

        return back()->withSuccess("Company profile updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $company = $request->tenant();
            $company->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting company.");
        }

        return redirect()->route('account.dashboard')
            ->withSuccess("{$company->name} deleted successfully.");
    }
}
