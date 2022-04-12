<?php

namespace Webkul\PWA\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewImage extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {   dd($this);
        return [
            'id' => $this->id,
            'path' => $this->path,
            'review_id' => $this->review_id
        ];
    }
}