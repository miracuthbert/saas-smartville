<?php

namespace Smartville\Http\Tenant\Controllers\Property;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Properties\Models\Property;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class PropertyDeleteController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Properties\Models\Property  $property
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property)
    {
        if (Gate::denies('delete property')) {
            return redirect()->route('tenant.dashboard');
        }

        $property->forceDelete();

        return back()->withSuccess("{$property->name} deleted completely.");
    }
}
