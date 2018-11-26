<?php

namespace Smartville\Http\Home\Controllers;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.index');
    }
}
