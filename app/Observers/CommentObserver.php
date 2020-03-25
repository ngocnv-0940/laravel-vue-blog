<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    public function created(Comment $comment)
    {
        $commentable = $comment->commentable;
        $commentable->increment('comments_count');
    }

    public function deleted(Comment $comment)
    {
        $comment->childs()->delete();
        $commentable = $comment->commentable;
        $commentable->update(['comments_count' => $commentable->comments()->count()]);
    }
}
