<?php

namespace Smartville\Http\Admin\Controllers\Page;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Smartville\Domain\Pages\Models\Page;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Admin\Requests\PageStoreRequest;
use Smartville\Http\Admin\Requests\PageUpdateRequest;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::withDepth()->defaultOrder()->with('parent')->get()->toFlatTree();

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PageStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageStoreRequest $request)
    {
        $page = new Page;
        $page->fill($request->except('order', 'page'));
        $page->save();

        // order page
        $pageOrdered = $page->setPageOrder($request);

        if ($pageOrdered !== true) {
            return session()->flash("error", "Failed when ordering page with error: {$pageOrdered->getMessage()}");
        }

        return redirect()->route('admin.pages.index')
            ->withSuccess("{$page->title} added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Pages\Models\Page $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Pages\Models\Page $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PageUpdateRequest $request
     * @param  \Smartville\Domain\Pages\Models\Page $page
     * @return \Illuminate\Http\Response
     */
    public function update(PageUpdateRequest $request, Page $page)
    {
        $pageOrdered = $page->setPageOrder($request);

        if ($pageOrdered !== true) {
            return back()->withInput()->withError("Failed when ordering page with error: {$pageOrdered->getMessage()}");
        }

        $page->fill($request->except('order', 'page'));
        $page->edited_at = Carbon::now();
        $page->save();

        return back()->withSuccess("Page updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Pages\Models\Page $page
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Page $page)
    {
        try {
            $page->descendants()->each(function ($node, $key) {
                $node->saveAsRoot();
            });

            $page->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting '{$page->title}' page. Please try again.");
        }

        return back()->withSuccess("Page '{$page->title}' deleted successfully.");
    }
}
