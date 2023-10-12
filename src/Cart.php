<?php
namespace Webkul\PWA;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Checkout\Repositories\CartAddressRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Tax\Repositories\TaxCategoryRepository;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\PWA\Helpers\Price;
use Webkul\Checkout\Cart as BaseCart;
use Webkul\Checkout\Facades\Cart as CartFacade;
use function React\Promise\all;

/**
 * Class Cart.
 *
 */
class Cart extends BaseCart
{
    /**
     * Suppress the session flash messages
    */
    protected $suppressFlash;

    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Checkout\Repositories\CartRepository  $cart
     * @param  Webkul\Checkout\Repositories\CartItemRepository  $cartItem
     * @param  Webkul\Checkout\Repositories\CartAddressRepository  $cartAddress
     * @param  Webkul\Customer\Repositories\CustomerRepository  $customer
     * @param  Webkul\Product\Repositories\ProductRepository  $product
     * @param  Webkul\Product\Repositories\TaxCategoryRepository  $taxCategory
     * @param  Webkul\Product\Repositories\CustomerAddressRepository  $customerAddress
     * @param  Webkul\Product\Repositories\CustomerAddressRepository  $customerAddress
     * @param  Webkul\Discount\Repositories\CartRuleRepository  $cartRule
     * @param  Webkul\Helpers\Discount  $discount
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected CartItemRepository $cartItemRepository,
        protected CartAddressRepository $cartAddressRepository,
        protected CustomerRepository $customerRepository,
        protected ProductRepository $productRepository,
        protected TaxCategoryRepository $taxCategoryRepository,
        protected WishlistRepository $wishlistRepository,
        protected CustomerAddressRepository $customerAddressRepository,
        protected Price $price
    )
    {
        parent::__construct(
            $cartRepository,
            $cartItemRepository,
            $cartAddressRepository,
            $productRepository,
            $taxCategoryRepository,
            $wishlistRepository,
            $customerAddressRepository,

        );
    }

    public function getCurrentCustomer()
    {
        $token = 0;

        if (request()->hasHeader('authorization')) {

            $headerValue = explode('Bearer ', request()->header('authorization'));

            if (isset($headerValue[1]) && $headerValue[1]) {
                $token = $headerValue[1];
            }
        }

        $guard = ($token || request()->has('token')) ? 'api' : 'customer';

        return auth()->guard($guard);
    }

    /**
     * Add items in a cart with some cart and item details.
     *
     * @param  int  $productId
     * @param  array  $data
     * @return \Webkul\Checkout\Contracts\Cart|string|array
     * @throws Exception
     */
    public function addProduct($productId, $data)
    {
        Event::dispatch('checkout.cart.add.before', $productId);

        $cart = $this->getCart();

        if (! $cart) {
            $cart = $this->create($data);
        }

        if (! $cart) {
            return ['warning' => __('shop::app.checkout.cart.item.error-add')];
        }

        $product = $this->productRepository->find($productId);

        if (! $product->status) {
            return ['info' => __('shop::app.checkout.cart.item.inactive-add')];
        }

        $cartProducts = $product->getTypeInstance()->prepareForCart($data);

        if (is_string($cartProducts)) {
            if ($cart->all_items->count() <= 0) {
                $this->removeCart($cart);
            } else {
                $this->collectTotals();
            }

            throw new Exception($cartProducts);
        } else {
            $parentCartItem = null;

            foreach ($cartProducts as $cartProduct) {
                $cartItem = $this->getItemByProduct($cartProduct, $data);

                if (isset($cartProduct['parent_id'])) {
                    $cartProduct['parent_id'] = $parentCartItem->id;
                }

                if (! $cartItem) {
                    $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
                } else {
                    if (
                        isset($cartProduct['parent_id'])
                        && $cartItem->parent_id !== $parentCartItem->id
                    ) {
                        $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, [
                            'cart_id' => $cart->id,
                        ]));
                    } else {
                        $cartItem = $this->cartItemRepository->update($cartProduct, $cartItem->id);
                    }
                }

                if (! $parentCartItem) {
                    $parentCartItem = $cartItem;
                }
            }
        }

        Event::dispatch('checkout.cart.add.after', $cart);

        $this->collectTotals();

        return $this->getCart();
    }


    /**
     * Create new cart instance.
     *
     * @param  array  $data
     * @return \Webkul\Checkout\Contracts\Cart|null
     */
    public function create($data)
    {
        $cartData = [
            'channel_id'            => core()->getCurrentChannel()->id,
            'global_currency_code'  => $baseCurrencyCode = core()->getBaseCurrencyCode(),
            'base_currency_code'    => $baseCurrencyCode,
            'channel_currency_code' => core()->getChannelBaseCurrencyCode(),
            'cart_currency_code'    => core()->getCurrentCurrencyCode(),
            'items_count'           => 1,
        ];

        /**
         * Fill in the customer data, as far as possible.
         */
        if ($this->getCurrentCustomer()) {
            $customer = $this->getCurrentCustomer()->user();

            $cartData = array_merge($cartData, [
                'customer_id'         => $customer->id,
                'is_guest'            => 0,
                'customer_first_name' => $customer->first_name,
                'customer_last_name'  => $customer->last_name,
                'customer_email'      => $customer->email,
            ]);
        } else {
            $cartData['is_guest'] = 1;
        }

        $cart = $this->cartRepository->create($cartData);

        if (! $cart) {
            session()->flash('error', __('shop::app.checkout.cart.create-error'));

            return;
        }

        $this->setCart($cart);
        $this->putCart($cart);

        return $cart;
    }

      /**
     * Returns cart.
     *
     * @return \Webkul\Checkout\Contracts\Cart|null
     */
    public function getCart(): ?\Webkul\Checkout\Contracts\Cart
    {
        $cart = BaseCart::getCart();

        if ($cart) {
            return $cart;
        }
    
        if ($this->getCurrentCustomer()->check()) {
            $customer = $this->getCurrentCustomer()->user();

            $cart = $this->cartRepository->findOneWhere([
                'customer_id' => $customer->id,
                'is_active'   => 1,
            ]);
        } elseif (session()->has('cart')) {
            $cart = $this->cartRepository->find(session()->get('cart')->id);
        }

        return $cart;
    }
}
