<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    public function created(Post $post)
    {
        $post->author()->increment('posts_count');
    }

    public function deleting(Post $post)
    {
        $post->author()->decrement('posts_count');
    }
}
