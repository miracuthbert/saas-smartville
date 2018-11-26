<?php

namespace Smartville\Http\Tenant\Controllers\Amenity;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Amenities\Models\Amenity;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Http\Tenant\Requests\AmenityStoreRequest;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('browse amenities')) {
            return redirect()->route('tenant.dashboard');
        }

        $amenities = Amenity::with('properties')->latest()->paginate();

        return view('tenant.amenities.index', compact('amenities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('create amenity')) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.amenities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AmenityStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AmenityStoreRequest $request)
    {
        if (Gate::denies('create amenity')) {
            return redirect()->route('tenant.dashboard');
        }

        $amenity = new Amenity();
        $amenity->fill($request->only('name', 'details', 'usable'));
        $amenity->all_properties = ($assign = $request->all_properties) != null ? true : false;
        $amenity->save();

        if ($assign) {
            $properties = Property::get();
            $amenity->properties()->syncWithoutDetaching($properties->pluck('id'));

            $msg = ($count = $properties->count()) . ' ' . str_plural('property', $count) . ' assigned amenity.';
            $request->session()->flash('info', $msg);
        }

        return redirect()->route('tenant.amenities.index')
            ->withSuccess("{$amenity->name} added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Amenities\Models\Amenity $amenity
     * @return \Illuminate\Http\Response
     */
    public function show(Amenity $amenity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Amenities\Models\Amenity $amenity
     * @return \Illuminate\Http\Response
     */
    public function edit(Amenity $amenity)
    {
        if (Gate::denies('update amenity')) {
            return redirect()->route('tenant.dashboard');
        }

        return view('tenant.amenities.edit', compact('amenity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AmenityStoreRequest $request
     * @param  \Smartville\Domain\Amenities\Models\Amenity $amenity
     * @return \Illuminate\Http\Response
     */
    public function update(AmenityStoreRequest $request, Amenity $amenity)
    {
        if (Gate::denies('update amenity')) {
            return redirect()->route('tenant.dashboard');
        }

        $amenity->fill($request->only('name', 'details', 'usable'));
        $amenity->all_properties = ($assign = $request->all_properties) != null ? true : false;
        $amenity->save();

        if ($assign) {
            $properties = Property::get();
            $amenity->properties()->syncWithoutDetaching($properties->pluck('id'));

            $msg = ($count = $properties->count()) . ' ' . str_plural('property', $count) . ' assigned amenity.';
            $request->session()->flash('info', $msg);
        }

        return back()->withSuccess("Amenity updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Amenities\Models\Amenity $amenity
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Amenity $amenity)
    {
        if (Gate::denies('delete amenity')) {
            return redirect()->route('tenant.dashboard');
        }

        try {
            $amenity->delete();
        } catch (\Exception $e) {
            return back()->witherror("Failed deleting {$amenity->name}. Please try again.");
        }

        return back()->withSuccess("{$amenity->name} deleted successfully");
    }
}
