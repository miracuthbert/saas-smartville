<?php

namespace Smartville\Http\Admin\Controllers\Feature;

use Carbon\Carbon;
use Smartville\Domain\Features\Models\Feature;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Admin\Requests\FeatureStoreRequest;
use Smartville\Http\Admin\Requests\FeatureUpdateRequest;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $features = Feature::with('parent')->defaultOrder()->get()->toFlatTree();

        return view('admin.features.index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.features.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FeatureStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeatureStoreRequest $request)
    {
        $feature = new Feature();
        $feature->fill($request->except('order', 'node'));
        $feature->save();

        $featureOrdered = $feature->setFeatureOrder($request);

        if ($featureOrdered !== true) {
            return session()->flash("error", "Failed when ordering page with error: {$featureOrdered->getMessage()}");
        }

        return redirect()->route('admin.features.index')
            ->withSuccess("Feature '{$feature->name}' added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Features\Models\Feature $feature
     * @return \Illuminate\Http\Response
     */
    public function show(Feature $feature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Features\Models\Feature $feature
     * @return \Illuminate\Http\Response
     */
    public function edit(Feature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FeatureUpdateRequest $request
     * @param  \Smartville\Domain\Features\Models\Feature $feature
     * @return \Illuminate\Http\Response
     */
    public function update(FeatureUpdateRequest $request, Feature $feature)
    {
        $featureOrdered = $feature->setFeatureOrder($request);

        if ($featureOrdered !== true) {
            return back()->withInput()
                ->withError("Failed when ordering page with error: {$featureOrdered->getMessage()}");
        }

        $feature->fill($request->except('order', 'node'));
        $feature->edited_at = Carbon::now();
        $feature->save();

        return back()->withSuccess("Feature updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Features\Models\Feature $feature
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Feature $feature)
    {
        try {
            // $feature->descendants()->each(function ($node, $key) {
            //     $node->saveAsRoot();
            // });

            $feature->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting '{$feature->name}' feature. Please try again.");
        }

        return back()->withSuccess("Feature '{$feature->name}' deleted successfully.");
    }
}
