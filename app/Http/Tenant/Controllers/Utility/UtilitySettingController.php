<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class UtilitySettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return view('tenant.utilities.settings.index');
    }
}
