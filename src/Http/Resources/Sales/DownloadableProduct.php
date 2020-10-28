<?php

namespace Webkul\PWA\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\API\Http\Resources\Core\Channel as ChannelResource;
use Webkul\API\Http\Resources\Customer\Customer as CustomerResource;
use Webkul\API\Http\Resources\Sales\Invoice;
use Webkul\API\Http\Resources\Sales\Shipment;
use Webkul\API\Http\Resources\Sales\OrderAddress;

class DownloadableProduct extends JsonResource
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
            'status'        => $this->status,
            'created_at'    => $this->created_at,
            'title'         => $this->product_name,
        ];
    }
}