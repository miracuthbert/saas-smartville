<?php

namespace Smartville\Http\Tenant\Controllers\Property;

use Illuminate\Support\Facades\Gate;
use Smartville\Domain\Properties\Models\PropertyFeature;
use Smartville\Domain\Properties\Models\Property;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Properties\Resources\PropertyFeatureCollection;
use Smartville\Domain\Properties\Resources\PropertyFeatureResource;
use Smartville\Http\Tenant\Requests\PropertyFeatureStoreRequest;

class PropertyFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return PropertyFeatureCollection
     */
    public function index(Property $property)
    {
        if (Gate::denies('update property')) {
            return response()->json(null, 404);
        }

        $features = $property->features;

        return new PropertyFeatureCollection($features);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PropertyFeatureStoreRequest $request
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @return PropertyFeatureResource
     */
    public function store(PropertyFeatureStoreRequest $request, Property $property)
    {
        if (Gate::denies('update property')) {
            return response()->json(null, 404);
        }

        $feature = new PropertyFeature;
        $feature->fill($request->only('name', 'count', 'details'));
        $feature->property()->associate($property);
        $feature->save();

        return new PropertyFeatureResource($feature);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Properties\Models\Property  $property
     * @param  \Smartville\Domain\Properties\Models\PropertyFeature  $propertyFeature
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property, PropertyFeature $propertyFeature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PropertyFeatureStoreRequest $request
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @param  \Smartville\Domain\Properties\Models\PropertyFeature $propertyFeature
     * @return \Illuminate\Http\Response
     */
    public function update(PropertyFeatureStoreRequest $request, Property $property, PropertyFeature $propertyFeature)
    {
        if (Gate::denies('update property')) {
            return response()->json(null, 404);
        }

        $propertyFeature->fill($request->only('name', 'count', 'details'));
        $propertyFeature->save();

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Properties\Models\Property $property
     * @param  \Smartville\Domain\Properties\Models\PropertyFeature $propertyFeature
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Property $property, PropertyFeature $propertyFeature)
    {
        if (Gate::denies('update property')) {
            return response()->json(null, 404);
        }

        try {
            $propertyFeature->delete();
        } catch (\Exception $e) {
            return response()->json(null, $e->getCode());
        }

        return response()->json(null, 204);
    }
}
