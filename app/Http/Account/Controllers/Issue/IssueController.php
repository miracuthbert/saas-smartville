<?php

namespace Smartville\Http\Account\Controllers\Issue;

use Smartville\Domain\Amenities\Models\Amenity;
use Smartville\Domain\Issues\Models\Issue;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Issues\Models\IssueTopic;
use Smartville\Domain\Issues\Resources\IssueIndexResource;
use Smartville\Domain\Issues\Resources\IssueResource;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Properties\Resources\PropertyResource;
use Smartville\Domain\Utilities\Models\Utility;

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
        $issues = $request->user()->issues()->with('topics.issueable', 'user')->filter($request)->paginate(3);

        return IssueIndexResource::collection($issues);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();

        $properties = Property::with('currentLease', 'amenities', 'utilities', 'company')
            ->whereHas('currentLease.user', function ($query) use ($user) {
                $query->where('id', $user->id);
            })->get();

        return PropertyResource::collection($properties);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|IssueResource
     */
    public function store(Request $request)
    {
        $property = Property::find($request->property_id);

        $issue = new Issue();
        $issue->fill($request->only('title', 'body'));
        $issue->user()->associate($request->user());
        $issue->save();

        $amenities = Amenity::findMany($request->amenity_ids);
        $amenities = $amenities->map(function ($amenity) {
            return (new IssueTopic())->issueable()->associate($amenity);
        });

        $utilities = Utility::findMany($request->utility_ids);
        $utilities = $utilities->map(function ($utility) {
            return (new IssueTopic())->issueable()->associate($utility);
        });

        $topics = array_merge([
            (new IssueTopic())->issueable()->associate($property),
            (new IssueTopic())->issueable()->associate($property->company)
        ], $amenities->all(), $utilities->all());

        $issue->topics()->saveMany($topics);

        $issue->load('topics.issueable');

        return new IssueResource($issue);
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

        return view('account.issues.show', compact('issue', 'topics'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Issues\Models\Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function edit(Issue $issue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Smartville\Domain\Issues\Models\Issue $issue
     * @return \Illuminate\Http\Response|IssueResource
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
     * @throws \Exception
     */
    public function destroy(Issue $issue)
    {
        if ($issue->isClosed()) {
            return back()->withError('Sorry! You cannot delete a closed issue.');
        }

        try {
            $issue->delete();
        } catch (\Exception $e) {
            return back()->withError('Failed deleting issue. Please try again!');
        }

        return redirect()->route('account.dashboard')
            ->withSuccess("{$issue->title} deleted from issues successfully.");
    }
}
