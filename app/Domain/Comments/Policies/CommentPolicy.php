<?php

namespace Smartville\Domain\Comments\Policies;

use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Comments\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the DocComment.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Comments\Models\Comment $comment
     * @return mixed
     */
    public function view(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can create DocDummyPluralModel.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }


    /**
     * Determine whether the user can reply to the Comment.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Comments\Models\Comment $comment
     * @return mixed
     */
    public function reply(User $user, Comment $comment)
    {
        if ($comment->isClosed()) {
            return false;
        }

        return !$comment->parent_id;
    }

    /**
     * Determine whether the user can update the DocComment.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Comments\Models\Comment $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        if (!$comment->owner) {
            return false;
        }

        return !$comment->isClosed();
    }

    /**
     * Determine whether the user can delete the DocComment.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Comments\Models\Comment $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return $comment->owner;
    }

    /**
     * Determine whether the user can restore the DocComment.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Comments\Models\Comment $comment
     * @return mixed
     */
    public function restore(User $user, Comment $comment)
    {
        return $comment->owner;
    }

    /**
     * Determine whether the user can permanently delete the DocComment.
     *
     * @param  \Smartville\Domain\Users\Models\User $user
     * @param  \Smartville\Domain\Comments\Models\Comment $comment
     * @return mixed
     */
    public function forceDelete(User $user, Comment $comment)
    {
        return $comment->owner;
    }
}
