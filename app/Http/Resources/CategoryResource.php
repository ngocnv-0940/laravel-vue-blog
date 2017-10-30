<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CategoryResource extends Resource
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
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'parent' => $this->when($this->parent_id, new CategoryResource($this->whenLoaded('parent'))),
            'childs' => $this->collection($this->whenLoaded('childs')),
            'post_count' => $this->when($this->posts_count, $this->posts_count)
        ];
    }
}
