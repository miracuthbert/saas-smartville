<?php

namespace Smartville\Http\Tenant\Controllers;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class TenantNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // todo: add company notifications permissions
//        if (Gate::denies('browse company notifications')) {
//            return redirect()->route('account.dashboard');
//        }

        return view('tenant.notifications.index');
    }
}
