<?php

namespace Smartville\Http\Account\Controllers\Company;

use Smartville\Domain\Company\Events\CompanyCreated;
use Smartville\Domain\Company\Models\Company;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Account\Requests\CompanyStoreRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = $request->user()->companies;

        return view('account.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('account.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CompanyStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyStoreRequest $request)
    {
        $company = new Company;
        $company->fill($request->all());
        $company->save();

        $request->user()->companies()->syncWithoutDetaching($company->id);

        event(new CompanyCreated($company, $request->user()));

        return redirect()->route('tenant.switch', $company)
            ->withSuccess('Company created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Company $company)
    {
        try {
            $company->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting company.");
        }

        return redirect()->route('account.companies.index')
            ->withSuccess("{$company->name} deleted successfully.");
    }
}
