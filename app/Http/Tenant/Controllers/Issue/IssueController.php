<?php

namespace Smartville\Http\Tenant\Controllers\Issue;

use Smartville\Domain\Issues\Models\Issue;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Issues\Resources\IssueIndexResource;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = $request->tenant();

        $issues = $company->issues()->with('topics.issueable', 'user')
            ->withCount('comments')
            ->filter($request)
            ->paginate(3);

        return (IssueIndexResource::collection($issues))->additional([
            'meta' => [
                'open' => $company->issues->where('closed_at', null)->count()
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Issues\Models\Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        $issue->load('user', 'topics.issueable');

        $topics = $issue->formattedTopics();

        return view('tenant.issues.show', compact('issue', 'topics'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Issues\Models\Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Issue $issue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Issues\Models\Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        //
    }
}
