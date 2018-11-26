<?php

namespace Smartville\Http\Support\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Support\Mails\SendContactEmail;
use Smartville\Http\Support\Requests\ContactStoreRequest;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContactStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactStoreRequest $request)
    {
        //todo: save to database

        $data = $request->only('name', 'email', 'subject', 'message');

        Mail::to('miracuthbert@gmail.com', 'Cuthbert Mirambo')->send(
            (new SendContactEmail(
                $request->name, $request->email, $request->subject, $request->message
            ))->delay(now()->addMinute())
        );

        return response()->json(null, 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
