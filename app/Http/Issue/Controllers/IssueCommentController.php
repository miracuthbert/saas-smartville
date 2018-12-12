<?php

namespace Smartville\Http\Issue\Controllers;

use Smartville\Domain\Comments\Models\Comment;
use Smartville\Domain\Comments\Resources\CommentResource;
use Smartville\Domain\Issues\Models\Issue;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Comment\Requests\CommentStoreRequest;

class IssueCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Smartville\Domain\Issues\Models\Issue $issue
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Issue $issue)
    {
        $comments = $issue->comments()->with('children', 'user')->paginate(3);

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentStoreRequest $request
     * @param  \Smartville\Domain\Issues\Models\Issue $issue
     * @return \Illuminate\Http\Response|CommentResource
     */
    public function store(CommentStoreRequest $request, Issue $issue)
    {
        $comment = $issue->comments()->make([
            'body' => $request->body,
        ]);

        $request->user()->comments()->save($comment);

        return new CommentResource($comment);
    }
}
