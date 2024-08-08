<?php

namespace Webkul\PWA\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\API\Http\Resources\Checkout\CartShippingRate;
use Webkul\API\Http\Resources\Checkout\CartPayment;
use Webkul\API\Http\Resources\Checkout\CartAddress;
use Webkul\API\Http\Resources\Core\Channel as ChannelResource;
use Webkul\API\Http\Resources\Customer\Customer as CustomerResource;

class Cart extends JsonResource
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
            'customer_email' => $this->customer_email,
            'customer_first_name' => $this->customer_first_name,
            'customer_last_name' => $this->customer_last_name,
            'shipping_method' => $this->shipping_method,
            'coupon_code' => $this->coupon_code,
            'is_gift' => $this->is_gift,
            'items_count' => $this->items_count,
            'items_qty' => $this->items_qty,
            'exchange_rate' => $this->exchange_rate,
            'global_currency_code' => $this->global_currency_code,
            'base_currency_code' => $this->base_currency_code,
            'channel_currency_code' => $this->channel_currency_code,
            'cart_currency_code' => $this->cart_currency_code,
            'grand_total' => $this->grand_total,
            'formated_grand_total' => core()->currency($this->base_grand_total),
            'base_grand_total' => $this->base_grand_total,
            'formated_base_grand_total' => core()->formatBasePrice($this->base_grand_total),
            'sub_total' => $this->sub_total,
            'formated_sub_total' => core()->currency($this->base_sub_total),
            'base_sub_total' => $this->base_sub_total,
            'formated_base_sub_total' => core()->formatBasePrice($this->base_sub_total),
            'tax_total' => $this->tax_total,
            'formated_tax_total' => core()->currency($this->base_tax_total),
            'base_tax_total' => $this->base_tax_total,
            'formated_base_tax_total' => core()->formatBasePrice($this->base_tax_total),
            'discount' => $this->discount_amount,
            'formated_discount' => core()->currency($this->base_discount_amount),
            'base_discount' => $this->base_discount_amount,
            'formated_base_discount' => core()->formatBasePrice($this->base_discount_amount),
            'checkout_method' => $this->checkout_method,
            'is_guest' => $this->is_guest,
            'is_active' => $this->is_active,
            'conversion_time' => $this->conversion_time,
            'customer' => $this->when($this->customer_id, new CustomerResource($this->customer)),
            'channel' => $this->when($this->channel_id, new ChannelResource($this->channel)),
            'items' => CartItem::collection($this->items),
            'selected_shipping_rate' => new CartShippingRate($this->selected_shipping_rate),
            'payment' => new CartPayment($this->payment),
            'billing_address' => new CartAddress($this->billing_address),
            'shipping_address' => new CartAddress($this->shipping_address),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}