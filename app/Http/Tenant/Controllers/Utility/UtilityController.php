<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Utilities\Models\Utility;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Tenant\Requests\UtilityStoreRequest;

class UtilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('utility.browse', Utility::class)) {
            return redirect()->route('tenant.dashboard');
        }

        $utilities = Utility::with('properties')->latest()->paginate();

        return view('tenant.utilities.index', compact('utilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('utility.create', Utility::class)) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.utilities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UtilityStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UtilityStoreRequest $request)
    {
        if (Gate::denies('utility.create', Utility::class)) {
            return redirect()->route('tenant.dashboard');
        }

        $utility = new Utility();
        $utility->fill($request->all());
        $utility->all_properties = ($assign = $request->all_properties) != null ? true : false;
        $utility->save();

        if ($assign) {
            $properties = Property::get();
            $utility->properties()->syncWithoutDetaching($properties->pluck('id'));

            $msg = ($count = $properties->count()) . ' ' . str_plural('property', $count) . ' assigned utility.';
            $request->session()->flash('info', $msg);
        }

        return redirect()->route('tenant.utilities.index')
            ->withSuccess("{$utility->name} service added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\Utility $utility
     * @return \Illuminate\Http\Response
     */
    public function show(Utility $utility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Utilities\Models\Utility $utility
     * @return \Illuminate\Http\Response
     */
    public function edit(Utility $utility)
    {
        if (Gate::denies('utility.update', $utility)) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.utilities.edit', compact('utility'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UtilityStoreRequest $request
     * @param  \Smartville\Domain\Utilities\Models\Utility $utility
     * @return \Illuminate\Http\Response
     */
    public function update(UtilityStoreRequest $request, Utility $utility)
    {
        if (Gate::denies('utility.update', $utility)) {
            return redirect()->route('tenant.dashboard');
        }

        $utility->fill($request->all());
        $utility->all_properties = ($assign = $request->all_properties) != null ? true : false;
        $utility->save();

        if ($assign) {
            $properties = Property::get();
            $utility->properties()->syncWithoutDetaching($properties->pluck('id'));

            $msg = ($count = $properties->count()) . ' ' . str_plural('property', $count) . ' assigned utility.';
            $request->session()->flash('info', $msg);
        }

        return back()->withSuccess("Utility updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Utilities\Models\Utility $utility
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Utility $utility)
    {
        if (Gate::denies('utility.delete', $utility)) {
            return redirect()->route('tenant.dashboard');
        }

        if ($utility->invoices->count() > 0) {
            return back()->withWarning("You cannot delete a utility which has invoices.");
        }

        try {
            $utility->delete();
        } catch (\Exception $e) {
            return back()->witherror("Failed deleting {$utility->name}. Please try again.");
        }

        return back()->withSuccess("{$utility->name} deleted successfully.");
    }
}
