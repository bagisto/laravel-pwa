<?php

namespace Webkul\PWA\Http\Resources\PWA;

use Illuminate\Http\Resources\Json\JsonResource;

class LayoutResource extends JsonResource
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
            'id'                => $this->id,
            'home_page_content' => $this->home_page_content,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}