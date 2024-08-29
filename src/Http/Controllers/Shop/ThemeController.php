<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Webkul\PWA\Http\Controllers\Controller;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

class ThemeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Theme\Repositories\ThemeCustomizationRepository  $themeCustomizationRepository
     * @return void
     */
    public function __construct(
        protected ThemeCustomizationRepository $themeCustomizationRepository
    ) {
    }

    /**
     * Get carousel images.
     */
    public function sliders()
    {
        $sliders = $this->themeCustomizationRepository->orderBy('sort_order')->findOneWhere([
            'status'     => 1,
            'channel_id' => core()->getCurrentChannel()->id,
            'type'       => 'image_carousel',
        ]);

        return $sliders->options;
    }
}
