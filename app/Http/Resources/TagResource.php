<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TagResource extends Resource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'posts_count' => $this->when($this->posts_count, $this->posts_count),
            'posts' => PostResource::collection($this->whenLoaded('posts'))
        ];
    }
}
