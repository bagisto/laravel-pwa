<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Support\Facades\DB;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\PWA\Http\Controllers\Controller;

class ProductController extends Controller
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
    public function getCustomerDownloadAbleProducts()
    {
        $customerId = request()->input('customer_id') ?? null;

        $result = DB::table('downloadable_link_purchased')
            ->distinct()
            ->leftJoin('orders', 'downloadable_link_purchased.order_id', '=', 'orders.id')
            ->leftJoin('invoices', 'downloadable_link_purchased.order_id', '=', 'invoices.order_id')
            ->addSelect('downloadable_link_purchased.*', 'invoices.state as invoice_state', 'orders.increment_id')
            ->addSelect(DB::raw('('.DB::getTablePrefix().'downloadable_link_purchased.download_bought - '.DB::getTablePrefix().'downloadable_link_purchased.download_canceled - '.DB::getTablePrefix().'downloadable_link_purchased.download_used) as remaining_downloads'))
            ->where('downloadable_link_purchased.customer_id', $customerId)->paginate(10);

        return response()->json([
            'data'    => $result,
        ]);
    }
}
