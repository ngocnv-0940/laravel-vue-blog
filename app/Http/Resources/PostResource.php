<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PostResource extends Resource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'content' => $this->content,
            'image' => $this->image,
            'featured' => (boolean) $this->featured,
            'is_public' => (boolean) $this->is_public,
            'comments_count' => $this->comments_count,
            'likes_count' => $this->likes_count,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'author' => new UserResource($this->whenLoaded('author')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'likes' => LikeResource::collection($this->whenLoaded('likes'))
        ];
    }
}
