<?php

namespace Smartville\Http\Tenant\Controllers\Rent;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class RentSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return view('tenant.rent.settings.index');
    }
}
