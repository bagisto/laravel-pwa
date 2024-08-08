<?php

namespace Webkul\PWA\Http\Resources\Core;

use Illuminate\Http\Resources\Json\JsonResource;

class Slider extends JsonResource
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
            'id'            => $this->id,
            'title'         => $this->title,
            'content'       => $this->content,
            'image_url'     => $this->image_url,
            'slider_path'   => $this->slider_path,
        ];
    }
}