<?php

namespace Smartville\Http\Admin\Controllers\Tutorial;

use Carbon\Carbon;
use Smartville\Domain\Tutorials\Models\Tutorial;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Admin\Requests\TutorialStoreRequest;
use Smartville\Http\Admin\Requests\TutorialUpdateRequest;

class TutorialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tutorials = Tutorial::with('parent')->paginate();

        return view('admin.tutorials.index', compact('tutorials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tutorials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TutorialStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TutorialStoreRequest $request)
    {
        $tutorial = new Tutorial();
        $tutorial->fill($request->except('order', 'node'));
        $tutorial->save();

        // order tutorial
        $tutorialOrder = $tutorial->setTutorialOrder($request);

        if ($tutorialOrder !== true) {
            return session()->flash("error", "Failed when ordering tutorial with error: {$tutorialOrder->getMessage()}");
        }

        return redirect()->route('admin.tutorials.index')
            ->withSuccess("{$tutorial->title} tutorial added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Tutorials\Models\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function show(Tutorial $tutorial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Tutorials\Models\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     */
    public function edit(Tutorial $tutorial)
    {
        return view('admin.tutorials.edit', compact('tutorial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TutorialUpdateRequest $request
     * @param  \Smartville\Domain\Tutorials\Models\Tutorial $tutorial
     * @return \Illuminate\Http\Response
     */
    public function update(TutorialUpdateRequest $request, Tutorial $tutorial)
    {
        $setTutorialOrder = $tutorial->setTutorialOrder($request);

        if ($setTutorialOrder !== true) {
            return back()->withInput()->withError("Failed when ordering tutorial with error: {$setTutorialOrder->getMessage()}");
        }

        $tutorial->fill($request->except('order', 'tutorial'));
        $tutorial->edited_at = Carbon::now();
        $tutorial->save();

        return back()->withSuccess("Tutorial updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Tutorials\Models\Tutorial  $tutorial
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Tutorial $tutorial)
    {
        try {
            $tutorial->descendants()->each(function ($node, $key) {
                $node->saveAsRoot();
            });

            $tutorial->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting '{$tutorial->title}' tutorial. Please try again.");
        }

        return back()->withSuccess("Tutorial '{$tutorial->title}' deleted successfully.");
    }
}
