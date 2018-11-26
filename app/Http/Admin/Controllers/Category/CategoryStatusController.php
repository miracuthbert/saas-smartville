<?php

namespace Smartville\Http\Admin\Controllers\Category;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Categories\Models\Category;

class CategoryStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \Smartville\Domain\Categories\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $status = $category->usable == true ? false : true;

        $category->update([
            'usable' => $status
        ]);

        $message = $status == true ? 'activated' : 'disabled';

        return back()->withSuccess("{$category->name} is {$message}.");
    }
}
