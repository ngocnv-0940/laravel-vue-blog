<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
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
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'posts_count' => $this->posts_count,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'posts' => PostResource::collection($this->whenLoaded('posts')),
        ];
    }
}
