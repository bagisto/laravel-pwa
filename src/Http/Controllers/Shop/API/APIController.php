<?php

namespace Webkul\PWA\Http\Controllers\Shop\API;

use Illuminate\Http\Request;
use Webkul\PWA\Http\Controllers\Controller;
use Webkul\API\Http\Resources\Catalog\Attribute;
use Webkul\Velocity\Helpers\Helper as VelocityHelper;
/**
 * Push Notification controller
 *
 * @author    Shubham Mehrotra <shubhammehrotra.symfony@webkul.com>@shubh-webkul
 * @copyright 2020 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * VelocityHelper object
     *
     * @var array
     */
    protected $velocityHelper;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Velocity\Helpers\Helper  $velocityHelper
     * @return void
     */
    public function __construct(
        VelocityHelper $velocityHelper
    ) {
        $this->_config = request('_config');

        $this->velocityHelper = $velocityHelper;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchAdvertisementImages()
    {
        $advertisementImages = null;
        $locale = request()->get('locale');

        $velocityMetaData = $this->velocityHelper->getVelocityMetaData();

        if ($velocityMetaData) {
            $advertisementImages = json_decode($velocityMetaData->advertisement, true);
        }

        if ($advertisementImages) {
            foreach ($advertisementImages as $sectionIndex => $advertisementSection) {
                foreach ($advertisementSection as $imageIndex => $imagePath) {
                    $advertisementImages[$sectionIndex][$imageIndex] = \Storage::url($advertisementImages[$sectionIndex][$imageIndex]);
                }
            }
        }

        return response()->json([
            'data'      => $advertisementImages ?? [],
        ]);
    }

    public function fetchAttributes()
    {
        $category = app('\Webkul\Category\Repositories\CategoryRepository')->find(request()->get('category_id'));
        $attributes = app('\Webkul\Product\Repositories\ProductFlatRepository')->getProductsRelatedFilterableAttributes($category);

        return response()->json([
            'data' => Attribute::collection($attributes),
        ]);
    }
}