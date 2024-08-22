<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\PWA\Http\Controllers\Controller;

class ReviewController extends Controller
{
    /**
     * Controller instance
     *
     * @param  Webkul\Product\Repositories\ProductReviewRepository  $reviewRepository
     */
    public function __construct(
        protected ProductReviewRepository $reviewRepository
    ) {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $customerId = request()->input('customer_id') ?? null;

        $this->validate(request(), [
            'customer_id'   => 'required',
        ]);

        $reviews = $this->reviewRepository
            ->where(['customer_id' => $customerId])
            ->with('product', 'images')
            ->paginate(5);

        foreach ($reviews as $review) {
            $review->product->image = $review->product->base_image_url;
        }

        return response()->json([
            'data'    => $reviews,
        ]);
    }

    /**
     * Get product single review by review id.
     */
    public function get()
    {
        $customerId = request()->input('customer_id') ?? null;

        $this->validate(request(), [
            'customer_id'   => 'required',
        ]);

        $review = $this->reviewRepository
            ->where(['id' => request()->id, 'customer_id' => $customerId])
            ->with('product', 'images')
            ->first();

        $review->product->image = $review->product->base_image_url;
        $review->product->reviews = $review->product->reviews;

        return response()->json([
            'data'    => $review,
        ]);
    }
}
