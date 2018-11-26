<?php

namespace Smartville\Http\Tenant\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('view company account')) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.account.index');
    }
}
