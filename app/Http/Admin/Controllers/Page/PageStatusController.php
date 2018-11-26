<?php

namespace Smartville\Http\Admin\Controllers\Page;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Pages\Models\Page;

class PageStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Smartville\Domain\Pages\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $status = $page->usable == true ? false : true;

        $page->update([
            'usable' => $status
        ]);

        $message = $status == true ? 'activated' : 'disabled';

        return back()->withSuccess("'{$page->title}' page is {$message}.");
    }
}
