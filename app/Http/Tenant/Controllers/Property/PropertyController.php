<?php

namespace Smartville\Http\Tenant\Controllers\Property;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Amenities\Models\Amenity;
use Smartville\Domain\Categories\Models\Category;
use Smartville\Domain\Properties\Models\Property;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Utilities\Models\Utility;
use Smartville\Http\Tenant\Requests\PropertyStoreRequest;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Gate::denies('browse properties')) {
            return redirect()->route('tenant.dashboard');
        }

        $properties = Property::with('company', 'category')
            ->finished()
            ->filter($request)
            ->inTrash($request)
            ->paginate();

        return view('tenant.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Property $property
     * @return \Illuminate\Http\Response
     */
    public function create(Property $property)
    {
        if (Gate::denies('create property')) {
            return redirect()->route('tenant.dashboard');
        }

        if (!$property->exists) {

            //create skeleton property
            $property = $this->createAndReturnSkeletonProperty();

            return redirect()->route('tenant.properties.create', $property);
        }

        $property->load('amenities', 'utilities');

        return view('tenant.properties.create', compact('property'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PropertyStoreRequest $request
     * @param Property $property
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyStoreRequest $request, Property $property)
    {
        if (Gate::denies('create property')) {
            return redirect()->route('tenant.dashboard');
        }

        $data = $request->only('name', 'image', 'overview_short', 'size', 'price', 'overview');
        $data = array_merge($data, ['finished' => true]);

        $property->slug = null;
        $property->category()->associate(Category::find($request->category_id));
        $property->update($data);

        // sync amenities
        $property->syncAmenities($request->amenities);

        // sync utilities
        $property->syncUtilities($request->utilities);

        return redirect()->route('tenant.properties.edit', $property)
            ->withSuccess("{$property->name} successfully added. Make changes and add tenant when ready.")
            ->withInfo("Switch to the features tab to add property features. Some default features have been created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function edit(Property $property)
    {
        if (Gate::denies('update property')) {
            return redirect()->route('tenant.dashboard');
        }

        // check if property initial step not completed
        if ($property->isNotFinished()) {
            return redirect()->route('tenant.properties.create', $property)
                ->withError('Please fill in the primary property details before proceeding.');
        }

        $property->load('amenities', 'utilities');

        return view('tenant.properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PropertyStoreRequest $request
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return \Illuminate\Http\Response
     */
    public function update(PropertyStoreRequest $request, Property $property)
    {
        if (Gate::denies('update property')) {
            return redirect()->route('tenant.dashboard');
        }

        $property->fill($request->only('name', 'image', 'overview_short', 'size', 'price', 'overview', 'live'));
        $property->category()->associate(Category::find($request->category_id));
        $property->save();

        // sync amenities
        $property->syncAmenities($request->amenities);

        // sync utilities
        $property->syncUtilities($request->utilities);

        // redirect to add tenant
        if ($request->add_tenant) {
            return redirect()->route('tenant.properties.invitations.create', $property)
                ->withSuccess("{$property->name} successfully updated. You can now add a tenant.");
        }

        return back()->withSuccess("{$property->name} successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Property $property)
    {
        if (Gate::denies('delete property')) {
            return redirect()->route('tenant.dashboard');
        }

        if ($property->leases->count() > 0) {
            return back()->withWarning("{$property->name} cannot be deleted since it has existing leases.")
                ->withInfo("You can only delete properties without leases or disable them to prevent leasing out.");
        }

        try {
            $property->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting {$property->name}.");
        }

        return back()->withSuccess("{$property->name} successfully deleted.");
    }

    /**
     * Creates and returns a (new)skeleton property.
     *
     * @return Property
     */
    protected function createAndReturnSkeletonProperty()
    {
        $property = new Property;
        $property->fill([
            'name' => uniqid(request()->tenant()->short_name),
            'image' => 'none',
            'overview_short' => 'None',
            'overview' => 'None',
            'currency' => \request()->tenant()->currency,
            'size' => 0.00,
            'price' => 0,
            'live' => false,
            'finished' => false,
        ]);
        $property->save();

        // add company wide amenities
        $property->amenities()->syncWithoutDetaching(Amenity::forAllProperties()->get()->pluck('id'));

        // add company wide utilities
        $property->utilities()->syncWithoutDetaching(Utility::forAllProperties()->get()->pluck('id'));

        return $property;

    }
}
