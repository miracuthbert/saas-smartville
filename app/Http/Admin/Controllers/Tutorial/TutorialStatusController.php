<?php

namespace Smartville\Http\Admin\Controllers\Tutorial;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Tutorials\Models\Tutorial;

class TutorialStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \Smartville\Domain\Tutorials\Models\Tutorial $tutorial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tutorial $tutorial)
    {
        $status = $tutorial->usable == true ? false : true;

        $tutorial->update([
            'usable' => $status
        ]);

        $message = $status == true ? 'activated' : 'disabled';

        return back()->withSuccess("'{$tutorial->title}' tutorial is {$message}.");
    }
}
