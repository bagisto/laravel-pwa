<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Support\Facades\Log;
use Webkul\API\Http\Controllers\Shop\Controller;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;


/**
 * Product controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ThemeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(protected ThemeCustomizationRepository $themeCustomizationRepository) {}

    /**
     * Get all sliders.
     *
     * @return \Illuminate\Http\Response
     */
    public function sliders()
    {
        $sliders = $this->themeCustomizationRepository->orderBy('sort_order')->findOneWhere([
            'status'     => 1,
            'channel_id' => core()->getCurrentChannel()->id,
            'type'      => 'image_carousel',
        ]);


        return $sliders->options;
    }
}
