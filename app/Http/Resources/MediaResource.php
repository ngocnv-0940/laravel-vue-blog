<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Models\Media;

class MediaResource extends Resource
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
            'url' => $this->is_local ? Media::url($this->url) : $this->url,
            'thumb' => $this->is_local ? Media::thumb($this->url) : $this->url,
        ];
    }
}
