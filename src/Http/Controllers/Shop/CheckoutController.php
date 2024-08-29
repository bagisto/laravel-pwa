<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Webkul\Checkout\Facades\Cart;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Payment\Facades\Payment;
use Webkul\RestApi\Http\Resources\V1\Shop\Checkout\CartResource;
use Webkul\RestApi\Http\Resources\V1\Shop\Checkout\CartShippingRateResource;
use Webkul\PWA\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    /**
     * Saves customer address.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveAddress()
    {
        $data = request()->all();
        $shipping_methods = Shipping::collectRates();

        if (
            Cart::hasError()
            || ! $shipping_methods
        ) {
            abort(400);
        }

        Cart::saveAddresses($data);

        $rates = [];

        foreach ($shipping_methods['shippingMethods'] as $shippingMethod) {
            $rates[] = [
                'carrier_title' => $shippingMethod['carrier_title'],
                'rates'         => CartShippingRateResource::collection(collect($shippingMethod['rates'])),
            ];
        }
        $cart = Cart::getCart();

        Cart::collectTotals();

        if ($cart->haveStockableItems()) {
            return response()->json([
                'data'    => [
                    'rates' => $rates,
                    'cart'  => new CartResource(Cart::getCart()),
                ],
                'nextStep'  => "shipping",
                'message' => trans('rest-api::app.shop.checkout.billing-address-saved'),
            ]);
        }

        return response()->json([
            'nextStep' => "payment",
            'cart'     => new CartResource(Cart::getCart()),
            'methods'  => Payment::getSupportedPaymentMethods(),
        ]);
    }
}
