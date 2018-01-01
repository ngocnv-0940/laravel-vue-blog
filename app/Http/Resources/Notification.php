<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Notification extends Resource
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
            'type' => snake_case(class_basename($this->type)),
            'data' => $this->data,
            'is_read' => !!$this->read_at,
            // 'created_at' => (string) $this->created_at,
        ];
    }
}
