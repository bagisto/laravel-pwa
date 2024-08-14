<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Webkul\PWA\Http\Controllers\Controller;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\CompareItemRepository;
use Webkul\Shop\Http\Resources\CompareItemResource;

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
     * @param  Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected CompareItemRepository $compareItemRepository,
    ) {
        // $this->guard = request()->has('token') ? 'api' : 'customer';

        // Log::info('Guard', $this->guard);
    }

    public function index()
    {
        $productIds = request()->input('product_ids') ?? [];
        $customerId = request()->input('customer_id') ?? null;

        /**
         * This will handle for customers.
         */
        if ($customerId) {
            $productIds = $this->compareItemRepository
                ->findByField('customer_id', $customerId)
                ->pluck('product_id')
                ->toArray();
        }

        $products = $this->productRepository
            ->whereIn('id', $productIds)
            ->get();


        // return CompareItemResource::collection($products);
        return response()->json([
            'status'  => 'success',
            'data' => CompareItemResource::collection($products),
        ]);
    }

    /**
     * function for customers to add product in comparison.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'product_id' => 'required|integer',
            'customer_id' => 'required|integer',
        ]);
        $customerId = $request->input('customer_id');
        $productId = $request->input('product_id');

        $compareProduct = $this->compareItemRepository->findOneByField([
            'customer_id'  => $customerId,
            'product_id'   => $productId,
        ]);

        if ($compareProduct) {
            return response()->json([
                'status'  => 'success',
                'message' => trans('shop::app.compare.already-added'),
            ]);
        }

        $this->compareItemRepository->create([
            'customer_id'  => $customerId,
            'product_id'   => $productId,
        ]);

        return response()->json([
            'action'  => 'added',
            'status'  => 'success',
            'message' => trans('shop::app.compare.item-add-success'),
        ]);
    }

    /**
     * function for customers to delete product in comparison.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->validate(request(), [
            'product_id' => 'required|integer',
            'customer_id' => 'required|integer',
        ]);

        $productId = request()->input('product_id') ?? null;
        $customerId = request()->input('customer_id') ?? null;

        $success = $this->compareItemRepository->deleteWhere([
            'product_id'  => $productId,
            'customer_id' => $customerId,
        ]);

        if (! $success) {
            return response()->json([
                'message' => trans('shop::app.compare.remove-error'),
            ]);
        }

        if ($customerId) {
            $productIds = $this->compareItemRepository
                ->findByField('customer_id', $customerId)
                ->pluck('product_id')
                ->toArray();
        }

        $products = $this->productRepository
            ->whereIn('id', $productIds ?? [])
            ->get();

        return response()->json([
            'data'    => CompareItemResource::collection($products),
            'message' => trans('shop::app.compare.remove-success'),
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
            'code'       => 'product_image',
            'admin_name' => __('velocity::app.customer.compare.product_image'),
        ]]);

        array_splice($comparableAttributes, 2, 0, [[
            'code'       => 'addToCartHtml',
            'admin_name' => __('velocity::app.customer.compare.actions'),
        ]]);

        return $comparableAttributes;
    }
}
