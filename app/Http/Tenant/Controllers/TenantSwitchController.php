<?php

namespace Smartville\Http\Tenant\Controllers;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Company\Events\CompanyUserLogin;
use Smartville\Domain\Company\Models\Company;

class TenantSwitchController extends Controller
{
    /**
     * Switch tenant.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function switch(Company $company)
    {
        event(new CompanyUserLogin(
            request()->user(),
            $company
        ));

        session()->put('tenant', $company->uuid);

        if (session()->has('success')) {
            session()->flash('success', session('success'));
        }

        if (session()->has('info')) {
            session()->flash('info', session('info'));
        }

        if (session()->has('danger')) {
            session()->flash('danger', session('danger'));
        }

        if (session()->has('warning')) {
            session()->flash('warning', session('warning'));
        }

        return redirect()->route('tenant.dashboard');
    }
}
