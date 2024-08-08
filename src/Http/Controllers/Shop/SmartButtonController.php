<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Webkul\Checkout\Facades\Cart;
use Webkul\Paypal\Http\Controllers\Controller;
use Webkul\Paypal\Payment\SmartButton;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;

class SmartButtonController extends Controller
{
    /**
     * SmartButton $smartButton
     *
     * @var \Webkul\Paypal\Payment\SmartButton
     */
    protected $smartButton;

    /**
     * OrderRepository $orderRepository
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * InvoiceRepository $invoiceRepository
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Paypal\Payment\SmartButton  $smartButton
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @return void
     */
    public function __construct(
        SmartButton $smartButton,
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->smartButton = $smartButton;

        $this->orderRepository = $orderRepository;

        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Paypal order creation for approval of client.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder()
    {
        try {
            return response()->json($this->smartButton->createOrder($this->buildRequestBody()));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Capturing paypal order after approval.
     *
     * @return \Illuminate\Http\Response
     */
    public function captureOrder()
    {
        try {
            $this->smartButton->captureOrder(request()->input('orderData.orderID'));
            return $this->saveOrder();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Build request body.
     *
     * @return array
     */
    protected function buildRequestBody()
    {
        $cart = Cart::getCart();

        $billingAddressLines = $this->getAddressLines($cart->billing_address->address1);

        $data = [
            'intent' => 'CAPTURE',

            'payer'  => [
                'name' => [
                    'given_name' => $cart->billing_address->first_name,
                    'surname'    => $cart->billing_address->last_name,
                ],

                'address' => [
                    'address_line_1' => current($billingAddressLines),
                    'address_line_2' => last($billingAddressLines),
                    'admin_area_2'   => $cart->billing_address->city,
                    'admin_area_1'   => $cart->billing_address->state,
                    'postal_code'    => $cart->billing_address->postcode,
                    'country_code'   => $cart->billing_address->country,
                ],

                'email_address' => $cart->billing_address->email,

                'phone' => [
                    'phone_type'   => 'MOBILE',

                    'phone_number' => [
                        'national_number' => $cart->billing_address->phone,
                    ],
                ],
            ],

            'application_context' => [
                'shipping_preference' => 'SET_PROVIDED_ADDRESS',
            ],

            'purchase_units' => [
                [
                    'amount'   => [
                        'value'         => (float) $cart->sub_total + $cart->tax_total + ($cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0) - $cart->discount_amount,
                        'currency_code' => $cart->cart_currency_code,

                        'breakdown'     => [
                            'item_total' => [
                                'currency_code' => $cart->cart_currency_code,
                                'value'         => (float) $cart->sub_total,
                            ],

                            'shipping'   => [
                                'currency_code' => $cart->cart_currency_code,
                                'value'         => (float) ($cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0),
                            ],

                            'tax_total'  => [
                                'currency_code' => $cart->cart_currency_code,
                                'value'         => (float) $cart->tax_total,
                            ],

                            'discount'   => [
                                'currency_code' => $cart->cart_currency_code,
                                'value'         => (float) $cart->discount_amount,
                            ],
                        ],
                    ],

                    'items'    => $this->getLineItems($cart),
                ],
            ]
        ];

        if ($cart->haveStockableItems() && $cart->shipping_address) {
            $data['purchase_units'][0] = array_merge($data['purchase_units'][0], [
                'shipping' => [
                    'address' => [
                        'address_line_1' => current($billingAddressLines),
                        'address_line_2' => last($billingAddressLines),
                        'admin_area_2'   => $cart->shipping_address->city,
                        'admin_area_1'   => $cart->shipping_address->state,
                        'postal_code'    => $cart->shipping_address->postcode,
                        'country_code'   => $cart->shipping_address->country,
                    ],
                ],
            ]);
        }

        return $data;
    }

    /**
     * Return cart items.
     *
     * @param  string  $cart
     * @return array
     */
    protected function getLineItems($cart)
    {
        $lineItems = [];

        foreach ($cart->items as $item) {
            $lineItems[] = [
                'unit_amount' => [
                    'currency_code' => $cart->cart_currency_code,
                    'value'         => (float) $item->price,
                ],
                'quantity'    => $item->quantity,
                'name'        => $item->name,
                'sku'         => $item->sku,
                'category'    => $item->product->getTypeInstance()->isStockable() ? 'PHYSICAL_GOODS' : 'DIGITAL_GOODS',
            ];
        }

        return $lineItems;
    }

    /**
     * Return convert multiple address lines into 2 address lines.
     *
     * @param  string  $address
     * @return array
     */
    protected function getAddressLines($address)
    {
        $address = explode(PHP_EOL, $address, 2);

        $addressLines = [current($address)];

        if (isset($address[1])) {
            $addressLines[] = str_replace(["\r\n", "\r", "\n"], ' ', last($address));
        } else {
            $addressLines[] = '';
        }

        return $addressLines;
    }

    /**
     * Saving order once captured and all formalities done.
     *
     * @return \Illuminate\Http\Response
     */
    protected function saveOrder()
    {
        if (Cart::hasError()) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        try {
            Cart::collectTotals();

            $this->validateOrder();

            $order = $this->orderRepository->create(Cart::prepareDataForOrder());

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $this->invoiceRepository->create($this->prepareInvoiceData($order));
            }

            Cart::deActivateCart();

            session()->flash('order', $order);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            session()->flash('error', trans('shop::app.common.error'));

            throw $e;
        }
    }

    /**
     * Prepares order's invoice data for creation.
     *
     * @param  \Webkul\Sales\Models\Order  $order
     * @return array
     */
    protected function prepareInvoiceData($order)
    {
        $invoiceData = ["order_id" => $order->id,];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    protected function validateOrder()
    {
        $cart = Cart::getCart();

        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        if (! $cart->checkMinimumOrder()) {
            throw new \Exception(trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));
        }

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