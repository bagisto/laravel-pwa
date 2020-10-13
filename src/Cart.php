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
use Webkul\Product\Helpers\Price;
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
            $customer,
            $product,
            $taxCategory,
            $wishlist,
            $customerAddress,
            $price
        );

        $this->product = $product;
    }

    /**
     * Save customer address
     *
     * @return Mixed
     */
    public function saveCustomerAddress($data)
    {
        if (! $cart = $this->getCart())
            return false;

        $billingAddress = $data['billing'];
        $shippingAddress = $data['shipping'];
        $billingAddress['cart_id'] = $shippingAddress['cart_id'] = $cart->id;

        if (isset($data['billing']['address_id']) && $data['billing']['address_id']) {
            $address = $this->customerAddress->findOneWhere(['id'=> $data['billing']['address_id']])->toArray();
            $billingAddress['first_name'] = $this->getCurrentCustomer()->user()->first_name;
            $billingAddress['last_name'] = $this->getCurrentCustomer()->user()->last_name;
            $billingAddress['email'] = $this->getCurrentCustomer()->user()->email;
            $billingAddress['address1'] = $address['address1'];
            $billingAddress['country'] = $address['country'];
            $billingAddress['state'] = $address['state'];
            $billingAddress['city'] = $address['city'];
            $billingAddress['postcode'] = $address['postcode'];
            $billingAddress['phone'] = $address['phone'];
        }

        if (isset($data['shipping']['address_id']) && $data['shipping']['address_id']) {
            $address = $this->customerAddress->findOneWhere(['id'=> $data['shipping']['address_id']])->toArray();
            $shippingAddress['first_name'] = $this->getCurrentCustomer()->user()->first_name;
            $shippingAddress['last_name'] = $this->getCurrentCustomer()->user()->last_name;
            $shippingAddress['email'] = $this->getCurrentCustomer()->user()->email;
            $shippingAddress['address1'] = $address['address1'];
            $shippingAddress['country'] = $address['country'];
            $shippingAddress['state'] = $address['state'];
            $shippingAddress['city'] = $address['city'];
            $shippingAddress['postcode'] = $address['postcode'];
            $shippingAddress['phone'] = $address['phone'];
        }

        if (isset($data['billing']['save_as_address']) && $data['billing']['save_as_address'] && ! isset($data['billing']['address_id'])) {
            $billingAddress['customer_id']  = $this->getCurrentCustomer()->user()->id;
            $this->customerAddress->create($billingAddress);
        }

        if (isset($data['shipping']['save_as_address']) && $data['shipping']['save_as_address']) {
            $shippingAddress['customer_id']  = $this->getCurrentCustomer()->user()->id;
            $this->customerAddress->create($shippingAddress);
        }

        if ($billingAddressModel = $cart->billing_address) {
            $this->cartAddress->update($billingAddress, $billingAddressModel->id);

            if ($shippingAddressModel = $cart->shipping_address) {
                if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                    $this->cartAddress->update($billingAddress, $shippingAddressModel->id);
                } else {
                    $this->cartAddress->update($shippingAddress, $shippingAddressModel->id);
                }
            } else {
                if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                    $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'shipping']));
                } else {
                    $this->cartAddress->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
                }
            }
        } else {
            $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'billing']));

            if (isset($billingAddress['use_for_shipping']) && $billingAddress['use_for_shipping']) {
                $this->cartAddress->create(array_merge($billingAddress, ['address_type' => 'shipping']));
            } else {
                $this->cartAddress->create(array_merge($shippingAddress, ['address_type' => 'shipping']));
            }
        }

        if ($this->getCurrentCustomer()->check()) {
            $cart->customer_email = $this->getCurrentCustomer()->user()->email;
            $cart->customer_first_name = $this->getCurrentCustomer()->user()->first_name;
            $cart->customer_last_name = $this->getCurrentCustomer()->user()->last_name;
        } else {
            $cart->customer_email = $cart->billing_address->email;
            $cart->customer_first_name = $cart->billing_address->first_name;
            $cart->customer_last_name = $cart->billing_address->last_name;
        }

        $cart->save();

        return true;
    }
}
