<?php

namespace Smartville\Http\Issue\Controllers;

use Smartville\Domain\Issues\Models\Issue;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Issues\Resources\IssueResource;

class IssueController extends Controller
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
        $issue->fill($request->only('body'));
        $issue->edited_at = now();
        $issue->save();

        return new IssueResource($issue);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Issues\Models\Issue $issue
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Issue $issue)
    {
        if ($issue->isClosed()) {
            return response()->json(null, 403);
        }

        try {
            $issue->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }

        return response()->json(null, 204);
    }
}
