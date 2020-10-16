<?php

namespace Webkul\PWA;

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

/**
 * Class Cart.
 *
 */
class Cart extends BaseCart
{

    /**
     * CartRepository instance
     *
     * @var mixed
     */
    protected $cart;

    /**
     * CartItemRepository instance
     *
     * @var mixed
     */
    protected $cartItem;

    /**
     * CustomerRepository instance
     *
     * @var mixed
     */
    protected $customer;

    /**
     * CartAddressRepository instance
     *
     * @var mixed
     */
    protected $cartAddress;

    /**
     * ProductRepository instance
     *
     * @var mixed
     */
    protected $product;

    /**
     * TaxCategoryRepository instance
     *
     * @var mixed
     */
    protected $taxCategory;

    /**
     * WishlistRepository instance
     *
     * @var mixed
     */
    protected $wishlist;

    /**
     * CustomerAddressRepository instance
     *
     * @var mixed
     */
    protected $customerAddress;

    /**
     * Suppress the session flash messages
    */
    protected $suppressFlash;

    /**
     * Product price helper instance
    */
    protected $price;

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
        CartRepository $cart,
        CartItemRepository $cartItem,
        CartAddressRepository $cartAddress,
        CustomerRepository $customer,
        ProductRepository $product,
        TaxCategoryRepository $taxCategory,
        WishlistRepository $wishlist,
        CustomerAddressRepository $customerAddress,
        Price $price
    )
    {
        parent::__construct(
            $cart,
            $cartItem,
            $cartAddress,
            $product,
            $taxCategory,
            $wishlist,
            $customerAddress
        );

        $this->product = $product;
    }
}
