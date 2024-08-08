<?php

namespace Webkul\PWA\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'id'                    => $this->id,
            'code'                  => $this->code,
            'name'                  => $this->name,
            'slug'                  => $this->slug,
            'status'                => $this->status,
            'image_url'             => $this->image_url,
            'meta_title'            => $this->meta_title,
            'description'           => $this->description,
            'display_mode'          => $this->display_mode,
            'meta_keywords'         => $this->meta_keywords,
            'meta_description'      => $this->meta_description,
            'show_products'         => $this->category_product_in_pwa,
            'category_icon_path'    => \Storage::url($this->category_icon_path),
            'additional'            => is_array($this->resource->additional)
                                        ? $this->resource->additional
                                        : json_decode($this->resource->additional, true),
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}