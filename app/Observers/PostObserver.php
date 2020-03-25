<?php

namespace App\Observers;

use DB;
use App\Models\Post;

class PostObserver
{
    public function created(Post $post)
    {
        if ($post->is_public) {
            $post->author()->increment('posts_count');
        }
    }

    public function updated(Post $post)
    {
        $author = $post->author;
        $posts_count = $author->posts()->count();
        $author->update(['posts_count' => $posts_count]);
    }

    public function deleting(Post $post)
    {
        DB::transaction(function () use ($post) {
            if ($post->is_public) {
                $post->author()->decrement('posts_count');
            }
            $post->comments()->delete();
            $post->likes()->delete();
        });
    }
}
