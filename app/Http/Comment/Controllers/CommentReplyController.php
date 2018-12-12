<?php

namespace Smartville\Http\Comment\Controllers;

use Smartville\Domain\Comments\Models\Comment;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Comments\Resources\CommentResource;
use Smartville\Http\Comment\Requests\CommentStoreRequest;

class CommentReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CommentStoreRequest $request
     * @param \Smartville\Domain\Comments\Models\Comment $comment
     * @return \Illuminate\Http\Response|CommentResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CommentStoreRequest $request, Comment $comment)
    {
        $this->authorize('reply', $comment);

        $reply = $comment->children()->make([
            'body' => $request->body
        ]);

        $reply->commentable()->associate($comment->commentable);

        request()->user()->comments()->save($reply);

        return new CommentResource($reply);
    }
}
