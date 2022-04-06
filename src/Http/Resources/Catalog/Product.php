<?php

namespace Webkul\PWA\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\API\Http\Resources\Catalog\Attribute;

class Product extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
        $this->productPriceHelper = app('Webkul\PWA\Helpers\Price');

        $this->productReviewHelper = app('Webkul\Product\Helpers\Review');

        $this->wishlistHelper = app('Webkul\Customer\Helpers\Wishlist');

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $product = $this->product ? $this->product : $this;

        $prices = $product->getTypeInstance()->getProductPrices();

        $productPrice = $this->calculatePrice($product);

        $data = [];

        switch ($product->type) {
            case 'grouped':
                $data['grouped_products'] = [];
                $groupedProducts = $product->grouped_products;

                foreach ($groupedProducts as $index => $groupedProduct) {
                    $associatedProduct = $groupedProduct->associated_product;

                    $data['grouped_products'][$index] = $associatedProduct->toArray();

                    $data['grouped_products'][$index] += [
                        'qty'                   => $groupedProduct->qty,
                        'isSaleable'            => $associatedProduct->getTypeInstance()->isSaleable(),
                        'formated_price'        => $associatedProduct->getTypeInstance()->getPriceHtml(),
                        'show_quantity_changer' => $associatedProduct->getTypeInstance()->showQuantityBox(),
                    ];
                }
            break;

            case 'downloadable':
                $data['downloadable_links'] = [];
                $data['downloadable_samples'] = [];

                $downloadableLinks = $product->downloadable_links;
                $downloadableSamples = $product->downloadable_samples;

                foreach ($downloadableSamples as $index => $downloadableSample) {
                    $sample = $downloadableSample->toArray();
                    $data['downloadable_samples'][$index] = $sample;
                    $data['downloadable_samples'][$index]['download_url'] = route('shop.downloadable.download_sample', ['type' => 'sample', 'id' => $sample['id']]);
                }

                foreach ($downloadableLinks as $index => $downloadableLink) {
                    $data['downloadable_links'][$index] = $downloadableLink->toArray();

                    if (isset($downloadableLink['sample_file'])) {
                        $data['downloadable_links'][$index]['price'] = core()->currency($downloadableLink->price);
                        $data['downloadable_links'][$index]['sample_download_url'] = route('shop.downloadable.download_sample', ['type' => 'link', 'id' => $downloadableLink['id']]);

                    }
                }
            break;

            case 'bundle':
                $options = [];
                $data['currency_options'] = core()->getAccountJsSymbols();
                $data['bundle_options'] = app('Webkul\Product\Helpers\BundleOption')->getBundleConfig($product);
            break;

            case 'booking':
                $bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->product_id);

                $data['booking_product'] = $bookingProduct;
                $data['booking_product']['slot_index_route'] = route('booking_product.slots.index', $data['booking_product']->id);

                if ($data['booking_product']->type == "appointment") {
                    $bookingSlotHelper = app('\Webkul\BookingProduct\Helpers\AppointmentSlot');

                    $data['booking_product']['today_slots_html'] = $bookingSlotHelper->getTodaySlotsHtml($bookingProduct);
                    $data['booking_product']['week_slot_durations'] = $bookingSlotHelper->getWeekSlotDurations($bookingProduct);
                    $data['booking_product']['appointment_slot'] = $bookingProduct->appointment_slot;
                }

                if ($data['booking_product']->type == "event") {
                    $bookingSlotHelper = app('\Webkul\BookingProduct\Helpers\EventTicket');

                    $data['booking_product']['tickets'] = $bookingSlotHelper->getTickets($bookingProduct);
                    $data['booking_product']['event_date'] = $bookingSlotHelper->getEventDate($bookingProduct);
                }

                if ($data['booking_product']->type == "rental") {
                    $data['booking_product']['renting_type'] = $bookingProduct->rental_slot->renting_type;
                }

                if ($data['booking_product']->type == "table") {
                    $bookingSlotHelper = app('\Webkul\BookingProduct\Helpers\TableSlot');

                    $data['booking_product']['today_slots_html'] = $bookingSlotHelper->getTodaySlotsHtml($bookingProduct);
                    $data['booking_product']['week_slot_durations'] = $bookingSlotHelper->getWeekSlotDurations($bookingProduct);
                    $data['booking_product']['table_slot'] = $bookingProduct->table_slot;
                }
            break;

            default:
            break;
        }

        if ($product->type != "grouped") {
            $data['show_quantity_changer'] = $product->getTypeInstance()->showQuantityBox();
        }

        $pwa_images = productimage()->getGalleryImages($product);
        $pwa_videos = productvideo()->getVideos($product);

        $pwa_videoData = $pwa_imageData = [];

        foreach ($pwa_videos as $key => $video) {
            $pwa_videoData[$key]['type'] = $video['type'];
            $pwa_videoData[$key]['large_image_url'] = $pwa_videoData[$key]['small_image_url']= $pwa_videoData[$key]['medium_image_url']= $pwa_videoData[$key]['original_image_url'] = $video['video_url'];
        }

        foreach ($pwa_images as $key => $image) {
            $pwa_imageData[$key]['type'] = '';
            $pwa_imageData[$key]['large_image_url']    = $image['large_image_url'];
            $pwa_imageData[$key]['small_image_url']    = $image['small_image_url'];
            $pwa_imageData[$key]['medium_image_url']   = $image['medium_image_url'];
            $pwa_imageData[$key]['original_image_url'] = $image['original_image_url'];
        }

        $pwa_images = array_merge($pwa_imageData, $pwa_videoData);

        $data +=  [
            'id'                     => $product->id,
            'type'                   => $product->type,
            'name'                   => $this->name,
            'url_key'                => $this->url_key,
            'price'                  => $product->getTypeInstance()->getMinimalPrice(),
            'formated_price'         => $product->getTypeInstance()->getPriceHtml(),
            'short_description'      => $this->short_description,
            'description'            => $this->description,
            'sku'                    => $this->sku,
            'images'                 => $pwa_images,
            'videos'                 => productvideo()->getVideos($product),
            'base_image'             => productimage()->getProductBaseImage($product),
            'variants'               => Self::collection($this->variants),
            'in_stock'               => $product->haveSufficientQuantity(1),
            $this->mergeWhen($product->getTypeInstance()->isComposite(), [
                'super_attributes' => Attribute::collection($product->super_attributes),
            ]),
            'special_price'          => $this->when(
                $product->getTypeInstance()->haveSpecialPrice(),
                $product->getTypeInstance()->getSpecialPrice()
            ),
            'formated_special_price' => $this->when(
                $product->getTypeInstance()->haveSpecialPrice(),
                core()->currency($product->getTypeInstance()->getSpecialPrice())
            ),
            'regular_price'          => $this->when(
                $product->getTypeInstance()->haveSpecialPrice(),
                data_get($prices, 'regular_price.price')
            ),
            'formated_regular_price' => $this->when(
                $product->getTypeInstance()->haveSpecialPrice(),
                data_get($prices, 'regular_price.formated_price')
            ),
            'reviews'                => [
                'total'          => $total = $this->productReviewHelper->getTotalReviews($product),
                'total_rating'   => $total ? $this->productReviewHelper->getTotalRating($product) : 0,
                'average_rating' => $total ? $this->productReviewHelper->getAverageRating($product) : 0,
                'percentage'     => $total ? json_encode($this->productReviewHelper->getPercentageRating($product)) : [],
            ],
            'is_saved'               => false,
            'is_wishlisted'          => $this->wishlistHelper->getWishlistProduct($product) ? true : false,
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
        ];

        return $data;
    }

    public function calculatePrice($product)
    {
        switch ($product->type) {
            case 'sample':
            case 'virtual':
                $price = $this->price;
                break;

            case 'configurable':
            case 'downloadable':
            case 'grouped':
            case 'bundle':
            case 'booking':
                $price = $product->getTypeInstance()->getPriceHtml();
                break;

            default:
                $price = $this->price;
                break;
        }

        return $price;
    }

    public function haveSufficientQuantity($product)
    {
        switch ($product->type) {
            case 'sample':
                $stock = $product->haveSufficientQuantity(1);
                break;

            case 'grouped':
            case 'configurable':
            case 'downloadable':
            case 'bundle':
            case 'booking':
                $stock = 1;
                break;

            default:
                $stock = 1;
                break;
        }

        return $stock;
    }
}