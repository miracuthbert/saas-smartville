<?php

namespace Smartville\Http\Comment\Controllers;

use Smartville\Domain\Comments\Models\Comment;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Comments\Resources\CommentResource;
use Smartville\Http\Comment\Requests\CommentStoreRequest;

class CommentController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param CommentStoreRequest $request
     * @param  \Smartville\Domain\Comments\Models\Comment $comment
     * @return \Illuminate\Http\Response|CommentResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CommentStoreRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update([
            'body' => $request->body
        ]);

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Comments\Models\Comment $comment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode());
        }

        return response()->json(null, 204);
    }
}
