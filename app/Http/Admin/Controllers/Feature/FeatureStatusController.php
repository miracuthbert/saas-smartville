<?php

namespace Smartville\Http\Admin\Controllers\Feature;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Features\Models\Feature;

class FeatureStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \Smartville\Domain\Features\Models\Feature $feature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feature $feature)
    {
        $status = $feature->usable == true ? false : true;

        $feature->update([
            'usable' => $status
        ]);

        $message = $status == true ? 'activated' : 'disabled';

        return back()->withSuccess("'{$feature->name}' feature is {$message}.");
    }
}
