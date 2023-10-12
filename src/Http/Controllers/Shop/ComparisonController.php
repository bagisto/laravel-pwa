<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Velocity\Helpers\Helper;
use Webkul\Velocity\Repositories\VelocityCustomerCompareProductRepository;
use Webkul\Product\Repositories\ProductRepository;

/**
 * Comparison controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2021 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ComparisonController extends Controller
{
     /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;


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
        protected Helper $velocityHelper,
        protected VelocityCustomerCompareProductRepository $compareProductsRepository,
        protected ProductRepository $productRepository
    ) {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        $this->_config = request('_config');

        if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {

            auth()->setDefaultDriver($this->guard);

            $this->middleware('auth:' . $this->guard);
        }

        $this->middleware('validateAPIHeader');

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

                if (auth()->guard($this->guard)->user()) {

                    $productCollection = $this->compareProductsRepository
                        ->leftJoin(
                            'products',
                            'velocity_customer_compare_products.product_id',
                            'products.id'
                        )
                        ->where('customer_id', auth()->guard($this->guard)->user()->id)
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
      
        $customerId = auth()->guard($this->guard)->user()->id;

        if ($product = $this->productRepository->findOrFail($productId)) {
            if (! $product->visible_individually) {
                abort(404);
            }
        }
        
        $compareProduct = $this->compareProductsRepository->findOneByField([
            'customer_id' => $customerId,
            'product_id'  => $productId,
        ]);
        
        if ($compareProduct) {
            return response()->json([
                'status'  => 'warning',
                'label'   => trans('velocity::app.shop.general.alert.warning'),
                'message' => trans('velocity::app.customer.compare.already_added'),
            ]);
        }

        $product = $this->productRepository->find($productId);
                        
        if (! $product) {
            return response()->json([
                'status'  => 'warning',
                'message' => trans('customer::app.product-removed'),
                'label'   => trans('velocity::app.shop.general.alert.warning'),
            ]);
        }

        $this->compareProductsRepository->create([
            'customer_id' => $customerId,
            'product_id'  => $product->id,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => trans('velocity::app.customer.compare.added'),
            'label'   => trans('velocity::app.shop.general.alert.success'),
        ]);
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
                'product_id'  => request()->get('productId'),
                'customer_id' => auth()->guard($this->guard)->user()->id,
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