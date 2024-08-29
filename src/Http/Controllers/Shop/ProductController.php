<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Product\Helpers\ConfigurableOption;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;
use Webkul\PWA\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Controller instance
     *
     * @param  Webkul\Product\Repositories\ProductReviewRepository  $reviewRepository
     */
    public function __construct(
        protected ProductReviewRepository $reviewRepository,
        protected ProductRepository $productRepository,
        protected ConfigurableOption $configurableOption,
        protected DownloadableLinkPurchasedRepository $downloadableLinkPurchasedRepository
    ) {}

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
            ->addSelect(DB::raw('(' . DB::getTablePrefix() . 'downloadable_link_purchased.download_bought - ' . DB::getTablePrefix() . 'downloadable_link_purchased.download_canceled - ' . DB::getTablePrefix() . 'downloadable_link_purchased.download_used) as remaining_downloads'))
            ->where('downloadable_link_purchased.customer_id', $customerId)->paginate(10);

        return response()->json([
            'data'    => $result,
        ]);
    }

    /**
     * Download prodduct
     *
     * @param int $id product id.
     */
    public function download($id)
    {
        $downloadableLinkPurchased = $this->downloadableLinkPurchasedRepository->findOneByField([
            'id'          => $id,
            'customer_id' => request()->input('customer_id'),
        ]);

        if ($downloadableLinkPurchased->status == 'pending') {
            abort(403);
        }

        $totalInvoiceQty = 0;

        if (isset($downloadableLinkPurchased->order->invoices)) {
            foreach ($downloadableLinkPurchased->order->invoices as $invoice) {
                $totalInvoiceQty = $totalInvoiceQty + $invoice->total_qty;
            }
        }

        $orderedQty = $downloadableLinkPurchased->order->total_qty_ordered;
        $totalInvoiceQty = $totalInvoiceQty * ($downloadableLinkPurchased->download_bought / $orderedQty);

        if (
            $downloadableLinkPurchased->download_used == $totalInvoiceQty
            || $downloadableLinkPurchased->download_used > $totalInvoiceQty
        ) {
            return response()->json([
                'warning' => trans('shop::app.customers.account.downloadable-products.download-error'),
            ]);
        }

        if (
            $downloadableLinkPurchased->download_bought
            && ($downloadableLinkPurchased->download_bought - ($downloadableLinkPurchased->download_used + $downloadableLinkPurchased->download_canceled)) <= 0
        ) {
            return response()->json([
                'warning' => trans('shop::app.customers.account.downloadable-products.download-error'),
            ]);
        }

        $remainingDownloads = $downloadableLinkPurchased->download_bought - ($downloadableLinkPurchased->download_used + $downloadableLinkPurchased->download_canceled + 1);

        if ($downloadableLinkPurchased->download_bought) {
            $this->downloadableLinkPurchasedRepository->update([
                'download_used' => $downloadableLinkPurchased->download_used + 1,
                'status'        => $remainingDownloads <= 0 ? 'expired' : $downloadableLinkPurchased->status,
            ], $downloadableLinkPurchased->id);
        }

        if ($downloadableLinkPurchased->type == 'file') {
            $privateDisk = Storage::disk('private');

            return $privateDisk->exists($downloadableLinkPurchased->file)
                ? $privateDisk->download($downloadableLinkPurchased->file)
                : abort(404);
        } else {
            $fileName = $name = substr($downloadableLinkPurchased->url, strrpos($downloadableLinkPurchased->url, '/') + 1);

            $tempImage = tempnam(sys_get_temp_dir(), $fileName);

            copy($downloadableLinkPurchased->url, $tempImage);

            return response()->download($tempImage, $fileName);
        }
    }

    /**
     * Get product configuration config.
     */
    public function configurableConfig()
    {
        $product = $this->productRepository->findOrFail(request()->id);
        $configurableOption = $this->configurableOption->getConfigurationConfig($product);

        return response()->json([
            'data'    => $configurableOption,
        ]);
    }
}
