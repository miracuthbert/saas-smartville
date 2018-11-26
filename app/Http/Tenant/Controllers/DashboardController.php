<?php

namespace Smartville\Http\Tenant\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the tenant's application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('browse company admin')) {
            return redirect()->route('account.dashboard');
        }

        return view('tenant.dashboard.index');
    }
}
