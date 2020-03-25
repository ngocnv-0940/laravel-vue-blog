<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CommentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'likes_count' => (int) $this->likes_count,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'childs' => CommentResource::collection($this->whenLoaded('childs')),
            'likes' => LikeResource::collection($this->whenLoaded('likes'))
        ];
    }
}
