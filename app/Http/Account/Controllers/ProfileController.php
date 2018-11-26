<?php

namespace Smartville\Http\Account\Controllers;

use Smartville\App\Controllers\Controller;
use Smartville\Http\Account\Requests\ProfileStoreRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the user profile view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.profile.index');
    }

    /**
     * Store user's profile details in storage.
     *
     * @param ProfileStoreRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileStoreRequest $request)
    {
        //update user
        $request->user()->update($request->except('password'));

        //redirect with success
        return back()->withSuccess('Profile updated successfully.');
    }
}
