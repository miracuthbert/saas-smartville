<?php

namespace Smartville\Http\Admin\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Smartville\App\Controllers\Controller;

class PermissionSeedController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function seed(Request $request)
    {
        Artisan::call('db:seed', [
            '--class' => \PermissionTableSeeder::class
        ]);

        return back()->withSuccess("Permissions seeding successful.");
    }
}
