<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Cart;
use Illuminate\Support\Facades\Event;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\PWA\Http\Resources\Checkout\Cart as CartResource;

/**
 * Cart controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * CartRepository object
     *
     * @var Object
     */
    protected $cartRepository;

    /**
     * WishlistRepository object
     *
     * @var \Webkul\Checkout\Repositories\WishlistRepository
     */
    protected $wishlistRepository;

    /**
     * CartItemRepository object
     *
     * @var Object
     */
    protected $cartItemRepository;

    /**
     * Controller instance
     *
     * @param Webkul\Checkout\Repositories\CartRepository     $cartRepository
     * @param Webkul\Checkout\Repositories\CartItemRepository $cartItemRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        WishlistRepository $wishlistRepository
    )
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        $this->wishlistRepository = $wishlistRepository;

        // $this->middleware('auth:' . $this->guard);

        $this->_config = request('_config');

        $this->cartRepository = $cartRepository;

        $this->cartItemRepository = $cartItemRepository;
    }

    /**
     * Get customer cart
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $customer = auth($this->guard)->user();

        $cart = Cart::getCart();

        if (
            ! auth()->guard('customer')->check()
            && method_exists($cart, 'hasDownloadableItems')
            && $cart->hasDownloadableItems()
        ) {
            $redirectToCustomerLogin = true;
        }

        return response()->json([
            'data'                      => $cart ? new CartResource($cart) : null,
            'redirectToCustomerLogin'   => $redirectToCustomerLogin ?? false,
            'isShipping'                => ($cart && $cart->haveStockableItems()) ? true : false,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        if (request()->get('is_buy_now')) {
            Event::dispatch('shop.item.buy-now', $id);
        }
        $data = request()->all();

        if (isset($data['links'])) {
            $tempLinks = $data['links'];
            $temArray = [];
            unset($data['links']);
            foreach ($tempLinks as $key => $value) {
                array_push($temArray, $key);
            }
            $data['links'] = $temArray;
        }

        Event::dispatch('checkout.cart.item.add.before', $id);

        try {
            $result = Cart::addProduct($id, $data);

            if (is_array($result) && isset($result['warning'])) {
                return response()->json([
                    'error' => $result['warning'],
                ], 400);
            }

            if ($customer = auth($this->guard)->user()) {
                $this->wishlistRepository->deleteWhere(['product_id' => $id, 'customer_id' => $customer->id]);
            }

            Event::dispatch('checkout.cart.item.add.after', $result);

            Cart::collectTotals();

            $cart = Cart::getCart();

            return response()->json([
                'message' => __('shop::app.checkout.cart.item.success'),
                'data'    => $cart ? new CartResource($cart) : null,
            ]);
        } catch (Exception $e) {
            Log::error('API CartController: ' . $e->getMessage(),
                ['product_id' => $id, 'cart_id' => cart()->getCart() ?? 0]);

            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                    'code'    => $e->getCode()
                ]
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        foreach (request()->get('qty') as $qty) {
            if ($qty <= 0) {
                return response()->json([
                        'message' => trans('shop::app.checkout.cart.quantity.illegal')
                    ], 401);
            }
        }

        foreach (request()->get('qty') as $itemId => $qty) {
            $item = $this->cartItemRepository->findOneByField('id', $itemId);

            Event::dispatch('checkout.cart.item.update.before', $itemId);

            Cart::updateItems(request()->all());

            Event::dispatch('checkout.cart.item.update.after', $item);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
                'message' => 'Cart updated successfully.',
                'data' => $cart ? new CartResource($cart) : null
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        Event::dispatch('checkout.cart.delete.before');

        Cart::deActivateCart();

        Event::dispatch('checkout.cart.delete.after');

        $cart = Cart::getCart();

        return response()->json([
                'message' => 'Cart removed successfully.',
                'data' => $cart ? new CartResource($cart) : null
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyItem($id)
    {
        Event::dispatch('checkout.cart.item.delete.before', $id);

        Cart::removeItem($id);

        Event::dispatch('checkout.cart.item.delete.after', $id);

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
                'message' => 'Cart removed successfully.',
                'data' => $cart ? new CartResource($cart) : null
            ]);
    }

    /**
     * Function to move a already added product to wishlist
     * will run only on customer authentication.
     *
     * @param instance cartItem $id
     */
    public function moveToWishlist($id)
    {
        if (auth()->guard('customer')->check()) {
            Event::dispatch('checkout.cart.item.move-to-wishlist.before', $id);

            Cart::moveToWishlist($id);

            Event::dispatch('checkout.cart.item.move-to-wishlist.after', $id);

            Cart::collectTotals();

            $cart = Cart::getCart();

            return response()->json([
                    'message' => 'Cart item moved to wishlist successfully.',
                    'data' => $cart ? new CartResource($cart) : null
                ]);
        } else {
            return response()->json([
                'message' => 'Warning: You are not authorised user.',
                'success' => false
            ], 400);
        }
    }

    /**
     * Apply coupon to the cart
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function applyCoupon()
    {
        $couponCode = request()->get('code');

        try {
            if (strlen($couponCode)) {
                Cart::setCouponCode($couponCode)->collectTotals();

                if (Cart::getCart()->coupon_code == $couponCode) {
                    return response()->json([
                        'success' => true,
                        'message' => trans('shop::app.checkout.total.success-coupon'),
                        'data' => [
                            'cart' => new CartResource(Cart::getCart())
                        ]
                        ], 200);
                }
            }

            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.invalid-coupon')
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.coupon-apply-issue')
            ], 400);
        }
    }

    /**
     * Apply coupon to the cart
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function removeCoupon()
    {
        Cart::removeCouponCode()->collectTotals();

        return response()->json([
            'success' => true,
            'message' => trans('shop::app.checkout.total.remove-coupon'),
            'data' => [
                'cart' => new CartResource(Cart::getCart())
            ]
        ]);
    }
}