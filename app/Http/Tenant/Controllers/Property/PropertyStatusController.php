<?php

namespace Smartville\Http\Tenant\Controllers\Property;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Properties\Models\Property;

class PropertyStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Property $property)
    {
        if (Gate::denies('update property')) {
            return redirect()->route('tenant.dashboard');
        }

        $status = $property->live == true ? false : true;

        $property->update([
            'live' => $status
        ]);

        $message = $status == true ? 'live' : 'disabled';

        return back()->withSuccess("{$property->name} is {$message}.");
    }
}
