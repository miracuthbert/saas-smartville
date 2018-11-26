<?php

namespace Smartville\Http\Admin\Controllers\Category;

use Smartville\Domain\Categories\Models\Category;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Admin\Requests\CategoryStoreRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('parent')->get()->toFlatTree();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get()->toFlatTree();

        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        $category = new Category;
        $category->fill($request->only('name', 'price', 'needs_auth'));
        $category->parent_id = $request->parent_id;
        $category->save();

        return redirect()->route('admin.categories.index')->withSuccess("{$category->name} successfully added.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Categories\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Categories\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::get()->toFlatTree();

        return view('admin.categories.edit', compact('categories', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryStoreRequest $request
     * @param  \Smartville\Domain\Categories\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryStoreRequest $request, Category $category)
    {
        $category->fill($request->only('name', 'usable', 'price', 'needs_auth'));
        $category->parent_id = $request->parent_id;
        $category->save();

        return back()->withSuccess('Category successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Categories\Models\Category $category
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting `{$category->name}` category.");
        }

        return back()->withSuccess("`{$category->name}` category successfully deleted.");
    }
}
