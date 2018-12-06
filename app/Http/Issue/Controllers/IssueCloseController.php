<?php

namespace Smartville\Http\Issue\Controllers;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Issues\Models\Issue;
use Smartville\Domain\Issues\Resources\IssueResource;

class IssueCloseController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Issues\Models\Issue $issue
     * @return \Illuminate\Http\Response|IssueResource
     */
    public function update(Request $request, Issue $issue)
    {
        $issue->closed_at = now();
        $issue->save();

        return new IssueResource($issue);
    }
}
