<?php

namespace Smartville\Http\Account\Controllers;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.notifications.index');
    }
}
