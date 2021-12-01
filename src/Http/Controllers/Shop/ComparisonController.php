<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Velocity\Helpers\Helper;
use Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\PWA\Http\Resources\Customer\Comparison as CompareResource;
use Webkul\API\Http\Resources\Checkout\Cart as CartResource;
use Cart;

/**
 * Comparison controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ComparisonController extends Controller
{

    /**
     * VelocityCustomerCompareProductRepository object of repository
     *
     * @var \Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository
     */
    protected $compareProductsRepository;

    /**
     * ProductRepository object
     *
     * @var object
     */
    protected $productRepository;

    /**
     * Helper object
     *
     * @var \Webkul\Velocity\Helpers\Helper
     */
    protected $velocityHelper;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Velocity\Helpers\Helper                                         $velocityHelper
     * @param  \Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository  $compareProductsRepository
     * @param Webkul\Product\Repositories\ProductRepository                            $productRepository
     *
     * @return void
     */
    public function __construct(
        Helper $velocityHelper,
        VelocityCustomerCompareProductRepository $compareProductsRepository,
        ProductRepository $productRepository
    ) {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        $this->velocityHelper = $velocityHelper;

        $this->compareProductsRepository = $compareProductsRepository;

        $this->productRepository = $productRepository;
    }

    /**
     * Method for customers to get products in comparison.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function getComparisonList()
    {
        if (! core()->getConfigData('general.content.shop.compare_option')) {
            abort(404);
        } else {
            if (request()->get('data')) {
                $productCollection = [];
                
                $comparableAttributes = [];

                if (auth()->guard('customer')->user()) {
                    $productCollection = $this->compareProductsRepository
                        ->leftJoin(
                            'product_flat',
                            'velocity_customer_compare_products.product_flat_id',
                            'product_flat.id'
                        )
                        ->where('customer_id', auth()->guard('customer')->user()->id)
                        ->get();

                    $items = $productCollection->map(function ($product) {
                        return $product->id;
                    })->join('&');

                    $productCollection = ! empty($items)
                        ? $this->velocityHelper->fetchProductCollection($items)
                        : [];
                } else {
                    /* for product details */
                    if ($items = request()->get('items')) {
                        $productCollection = $this->velocityHelper->fetchProductCollection($items);
                    }
                }

                if ($productCollection) {
                    $comparableAttributes = $this->getComparableAttributes();
                }

                $response = [
                    'status'   => 'success',
                    'products' => $productCollection,
                    'comparableAttributes' => $comparableAttributes,
                ];

            } else {
                $response = view($this->_config['view']);
            }

            return $response;
        }
    }

    /**
     * function for customers to add product in comparison.
     *
     * @return \Illuminate\Http\Response
     */
    public function addCompareProduct()
    {           
        $productId = request()->get('productId');
      
        $customerId = auth()->guard('customer')->user()->id;
        
        $compareProduct = $this->compareProductsRepository->findOneByField([
            'customer_id'     => $customerId,
            'product_flat_id' => $productId,
        ]);
        
        if (! $compareProduct) {
            // insert new row

            $productFlatRepository = app('\Webkul\Product\Models\ProductFlat');

            $productFlat = $productFlatRepository
                            ->where('product_id', $productId)
                            ->get()
                            ->first();

            if ($productFlat) {
                $productId = $productFlat->id;
                $this->compareProductsRepository->create([
                    'customer_id'     => $customerId,
                    'product_flat_id' => $productId,
                ]);
            }

            return response()->json([
                'status'  => 'success',
                'message' => trans('velocity::app.customer.compare.added'),
                'label'   => trans('velocity::app.shop.general.alert.success'),
            ], 201);
        } else {
            return response()->json([
                'status'  => 'success',
                'label'   => trans('velocity::app.shop.general.alert.success'),
                'message' => trans('velocity::app.customer.compare.already_added'),
            ], 200);
        }
    }

    /**
     * function for customers to delete product in comparison.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteComparisonProduct()
    {
        // either delete all or individual
        if (request()->get('productId') == 'all') {
            // delete all
            $customerId = auth()->guard('customer')->user()->id;
            $this->compareProductsRepository->deleteWhere([
                'customer_id' => auth()->guard('customer')->user()->id,
            ]);
            $message = trans('velocity::app.customer.compare.removed-all');
        } else {
            // delete individual
            $this->compareProductsRepository->deleteWhere([
                'product_flat_id' => request()->get('productId'),
                'customer_id'     => auth()->guard('customer')->user()->id,
            ]);
            $message = trans('velocity::app.customer.compare.removed');
        }

        return [
            'status'  => 'success',
            'message' => $message,
            'label'   => trans('velocity::app.shop.general.alert.success'),
        ];
            
    }

    /**
     * This function will provide details of multiple product
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetailedProducts()
    {
        // for product details
        if ($items = request()->get('items')) {
            $comparableAttributes = [];

            $moveToCart = request()->get('moveToCart');

            $productCollection = $this->velocityHelper->fetchProductCollection($items, $moveToCart);

            if ($productCollection) {
                $comparableAttributes = $this->getComparableAttributes();
            }

            $response = [
                'status'   => 'success',
                'products' => $productCollection,
                'comparableAttributes' => $comparableAttributes,
            ];
        }

        return response()->json($response ?? [
            'status' => false
        ]);
    }


     /**
     * Get Comparable Attributes.
     *
     * @return array
     */
    public function getComparableAttributes()
    {
        $attributeRepository = app('\Webkul\Attribute\Repositories\AttributeFamilyRepository');
        $comparableAttributes = $attributeRepository->getComparableAttributesBelongsToFamily();

        $locale = request()->get('locale') ?: app()->getLocale();

        $attributeOptionTranslations = app('\Webkul\Attribute\Repositories\AttributeOptionTranslationRepository')->where('locale', $locale)->get()->toJson();
                
        $comparableAttributes = $comparableAttributes->toArray();

        array_splice($comparableAttributes, 1, 0, [[
            'code' => 'product_image',
            'admin_name' => __('velocity::app.customer.compare.product_image'),
        ]]);

        array_splice($comparableAttributes, 2, 0, [[
            'code' => 'addToCartHtml',
            'admin_name' => __('velocity::app.customer.compare.actions'),
        ]]);

        return $comparableAttributes;
    }
}