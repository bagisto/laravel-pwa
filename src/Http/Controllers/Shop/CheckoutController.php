<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Support\Facades\Log;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Payment\Facades\Payment;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\PWA\Http\Resources\Checkout\Cart as CartResource;
use Webkul\API\Http\Resources\Checkout\CartShippingRate as CartShippingRateResource;
use Webkul\Checkout\Facades\Cart;
use Webkul\PWA\Http\Resources\Sales\Order as OrderResource;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Sales\Repositories\OrderRepository;

/**
 * Checkout controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CheckoutController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * Controller instance
     *
     * @param Webkul\Checkout\Repositories\CartRepository     $cartRepository
     * @param Webkul\Checkout\Repositories\CartItemRepository $cartItemRepository
     * @param Webkul\Sales\Repositories\OrderRepository       $orderRepository
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository,
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository
    ) {
        $this->guard = request()->has('token') ? 'api' : 'customer';
        
        auth()->setDefaultDriver($this->guard);
        
        $this->middleware('auth:' . $this->guard);

        $this->middleware('validateAPIHeader');
        
        $this->_config = request('_config');

    }

    /**
     * Saves customer address.
     *
     * @param  \Webkul\Checkout\Http\Requests\CustomerAddressForm $request
     * @return \Illuminate\Http\Response
    */
    public function saveAddress(CustomerAddressForm $request)
    {
        $data = request()->all();
        
        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));
        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (isset($data['billing']['id']) && str_contains($data['billing']['id'], 'address_')) {
            unset($data['billing']['id']);
            unset($data['billing']['address_id']);
        }

        if (isset($data['shipping']['id']) && str_contains($data['shipping']['id'], 'address_')) {
            unset($data['shipping']['id']);
            unset($data['shipping']['address_id']);
        }

        if (
            Cart::hasError()
            || ! Cart::saveCustomerAddress($data)
        ) {
            return response()->json([
                    'error' => 'warning',
                ], 400);
        }


        $cart = Cart::getCart();

        Cart::collectTotals();
        
        if ($cart->haveStockableItems()) {
            if (! $rates = Shipping::collectRates()) {
                abort(400);
            }

            return response()->json([
                'rates'     => $rates,
                'nextStep'  => "shipping",
            ]);
        }

        return response()->json([
            'nextStep'  => "payment",
            'methods'   => Payment::getSupportedPaymentMethods(),
            ]);
    }

    /**
     * Saves shipping method.
     *
     * @return \Illuminate\Http\Response
    */
    public function saveShipping()
    {
        $shippingMethod = request()->get('shipping_method');

        if (Cart::hasError() || !$shippingMethod || ! Cart::saveShippingMethod($shippingMethod))
            abort(400);

        Cart::collectTotals();

        return response()->json([
            'data' => [
                'methods' => Payment::getPaymentMethods(),
                'cart' => new CartResource(Cart::getCart())
            ]
        ]);
    }

    /**
     * Check Guest Checkout.
     *
     * @return \Illuminate\Http\Response
    */
    public function isGuestCheckout()
    {
        $cart = Cart::getCart();
        
        if (! auth()->guard($this->guard )->check()
            && ! core()->getConfigData('catalog.products.guest-checkout.allow-guest-checkout')) {
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }

        if (! auth()->guard($this->guard )->check() && ! $cart->hasGuestCheckoutItems()) {
            return response()->json([
                'data' => [
                    'status' => false
                ]
            ]);
        }

        return response()->json([
            'data' => [
                'status' => true
            ]
        ]);
    }

    /**
     * Saves payment method.
     *
     * @return \Illuminate\Http\Response
    */
    public function savePayment()
    {
        $payment = request()->get('payment');

        if (Cart::hasError() || ! $payment || ! Cart::savePaymentMethod($payment))
            abort(400);

        return response()->json([
            'data' => [
                'cart' => new CartResource(Cart::getCart())
            ]
        ]);
    }

    /**
     * Saves order.
     *
     * @return \Illuminate\Http\Response
    */
    public function saveOrder()
    {
        if (Cart::hasError()) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        $this->validateOrder();

        $cart = Cart::getCart();

        if ($redirectUrl = Payment::getRedirectUrl($cart)) {
            return response()->json([
                'success'      => true,
                'redirect_url' => $redirectUrl,
            ]);
        }

        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        Cart::deActivateCart();

        session()->flash('order', $order);

        return response()->json([
            'success'   => true,
            'order'     => [
                "id" => $order->increment_id
            ],
        ]);
    }

    /**
     * Validate order before creation
     *
     * @return mixed
     */
    public function validateOrder()
    {
        $cart = Cart::getCart();

        if ($cart->haveStockableItems() && ! $cart->shipping_address) {
            throw new \Exception(trans('Please check shipping address.'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('Please check billing address.'));
        }

        if ($cart->haveStockableItems() && ! $cart->selected_shipping_rate) {
            throw new \Exception(trans('Please specify shipping method.'));
        }

        if (! $cart->payment) {
            throw new \Exception(trans('Please specify payment method.'));
        }
    }
}