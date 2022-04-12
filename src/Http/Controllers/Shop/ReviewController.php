<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\PWA\Http\Resources\Catalog\ProductReview as ProductReviewResource;
use Webkul\Product\Repositories\ProductReviewImageRepository;

/**
 * Review controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * ProductReviewRepository object
     *
     * @var object
     */
    protected $reviewRepository;

    /**
     * ProductReviewImageRepository object
     *
     * @var \Webkul\Product\Repositories\ProductReviewImageRepository
     */
    protected $productReviewImageRepository;

    /**
     * Controller instance
     *
     * @param Webkul\Product\Repositories\ProductReviewRepository $reviewRepository
     * @param  \Webkul\Product\Repositories\ProductReviewImageRepository  $productReviewImageRepository
     */
    public function __construct(
        ProductReviewRepository $reviewRepository,
        ProductReviewImageRepository $productReviewImageRepository
    )
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        $this->reviewRepository = $reviewRepository;

        $this->productReviewImageRepository = $productReviewImageRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $customer = auth($this->guard)->user();

        $this->validate(request(), [
            'comment' => 'required',
            'rating'  => 'required|numeric|min:1|max:5',
            'title'   => 'required',
        ]);

        $data = request()->all();
        
        $productReview = $this->reviewRepository->create([
            'customer_id' => $customer ? $customer->id : null,
            'name'        => $customer ? $customer->name : $request->get('name'),
            'status'      => 'pending',
            'product_id'  => $id,
            'comment'     => $request->comment,
            'rating'      => $request->rating,
            'title'       => $request->title
        ]);

        $this->productReviewImageRepository->uploadImages($data, $productReview);

        return response()->json([
                'message' => 'Your review submitted successfully.',
                'data' => new ProductReviewResource($this->reviewRepository->find($productReview->id))
            ]);
    }
}