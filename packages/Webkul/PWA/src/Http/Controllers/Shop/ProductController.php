<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\PWA\Http\Resources\Catalog\Product as ProductResource;

/**
 * Product controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @author    Vivek Sharma <viveksh047@webkul.com>@vivek-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository $productRepository
     * @return void
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductResource::collection($this->productRepository->getAll(request()->input('category_id')));
    }

    /**
     * Returns a individual resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        return new ProductResource(
                $this->productRepository->findOrFail($id)
            );
    }

    /**
     * Returns product's additional information.
     *
     * @return \Illuminate\Http\Response
     */
    public function configurableConfig($id)
    {
        return response()->json([
                'data' => app('Webkul\PWA\Helpers\PwaConfigurableOption')->getConfigurationConfig($this->productRepository->findOrFail($id))
            ]);
    }

    /**
     * Return product options
     *
     * @return \Illuminate\Http\Response
     */
    public function bundleConfig($id)
    {
        return response()->json([
            'data' => app('Webkul\PWA\Helpers\PwaBundleOption')->getBundleConfig($this->productRepository->findOrFail($id)),
        ]);
    }
}
