<?php

namespace App\Observers;

use App\Models\Like;

class LikeObserver
{
    public function created(Like $like)
    {
        $likeable = $like->likeable;
        $likeable->increment('likes_count');
    }

    public function deleted(Like $like)
    {
        $likeable = $like->likeable;
        $likeable->decrement('likes_count');
    }
}
