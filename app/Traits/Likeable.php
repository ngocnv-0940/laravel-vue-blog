<?php

namespace App\Traits;

use App\Http\Resources\LikeResource;

trait Likeable
{
    /**
     * Like or unlike
     * @return Illuminate\Http\Response
     */
    public function likeOrUnlike()
    {
        if (!auth()->check()) {
            abort(500, 'Please login to do this action!');
        }

        $authId = auth()->id();
        $like = $this->likes()->where('user_id', $authId)->first();

        if ($like) {
            $like->delete();
            return [
                'action' => 'unlike',
                'like' => new LikeResource($like)
            ];
        }

        $newLike = $this->likes()->create(['user_id' => $authId]);
        return [
            'action' => 'like',
            'like' => new LikeResource($newLike)
        ];
    }
}
