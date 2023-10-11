<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Checkout\Cart as CheckoutCart;
use Webkul\Checkout\Facades\Cart as FacadesCart;
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
     * Controller instance
     *
     * @param Webkul\Checkout\Repositories\CartRepository     $cartRepository
     * @param Webkul\Checkout\Repositories\CartItemRepository $cartItemRepository
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository,
        protected WishlistRepository $wishlistRepository
    )
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        $this->_config = request('_config');

        if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {

            auth()->setDefaultDriver($this->guard);

            $this->middleware('auth:' . $this->guard);
        }

        $this->middleware('validateAPIHeader');

    }

    /**
     * Get customer cart
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $customer = auth($this->guard)->user();
        $cart = FacadesCart::getCart();

        if(! empty($cart)) {
            if (

                method_exists($cart, 'hasDownloadableItems')
                && ! auth()->guard('customer')->check()
                && $cart->hasDownloadableItems()
            ) {
                $redirectToCustomerLogin = true;
            }
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
            $result = FacadesCart::addProduct($id, $data);          

            if (is_array($result) && isset($result['warning'])) {
                return response()->json([
                    'error' => $result['warning'],
                ], 400);
            }

            if ($customer = auth($this->guard)->user()) {
                $this->wishlistRepository->deleteWhere(['product_id' => $id, 'customer_id' => $customer->id]);
            }

            Event::dispatch('checkout.cart.item.add.after', $result);
            cart()->collectTotals();
            $cart = cart()->getCart();
            
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

            cart()->updateItems(request()->all());

            Event::dispatch('checkout.cart.item.update.after', $item);
        }

        cart()->collectTotals();
        $cart = cart()->getCart();

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

        cart()->deActivateCart();

        Event::dispatch('checkout.cart.delete.after');

        $cart = cart()->getCart();

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
         FacadesCart::removeItem($id);

         $cart = FacadesCart::getCart();

         session(['cart' => $cart]);

        return response()->json([
                'status'  => true,
                'message' => 'Cart removed successfully.',
                'data'    => $cart,
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

            cart()->moveToWishlist($id);

            Event::dispatch('checkout.cart.item.move-to-wishlist.after', $id);

            cart()->collectTotals();

            $cart = cart()->getCart();

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
                cart()->setCouponCode($couponCode)->collectTotals();

                if (cart()->getCart()->coupon_code == $couponCode) {
                    return response()->json([
                        'success' => true,
                        'message' => trans('shop::app.checkout.total.success-coupon'),
                        'data' => [
                            'cart' => new CartResource(cart()->getCart())
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
        cart()->removeCouponCode()->collectTotals();

        return response()->json([
            'success' => true,
            'message' => trans('shop::app.checkout.total.remove-coupon'),
            'data' => [
                'cart' => new CartResource(cart()->getCart())
            ]
        ]);
    }
}