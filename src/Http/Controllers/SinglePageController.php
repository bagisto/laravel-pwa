<?php

namespace Webkul\PWA\Http\Controllers;

use Detection\MobileDetect;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

/**
 * Home page controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SinglePageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected ThemeCustomizationRepository $themeCustomizationRepository)
    {
    }

    /**
     * loads the home page for the storefront
     */
    public function home()
    {
        $agent = new MobileDetect;

        if ($agent->isMobile() || $agent->isTablet()) {
            return redirect('/mobile');
        }

        $currentChannel = core()->getCurrentChannel();

        $sliderData = app('Webkul\Core\Repositories\SliderRepository')->findByField('channel_id', $currentChannel->id)->toArray();

        return view('shop::home.index', compact('sliderData'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!core()->getConfigData('pwa.settings.general.status')) {
            session()->flash('warning', trans('pwa::app.shop.home.enable-pwa-status'));

            return redirect()->route('shop.home.index');
        }

        visitor()->visit();

        $customizations = $this->themeCustomizationRepository->orderBy('sort_order')->findWhere([
            'status'     => 1,
            'channel_id' => core()->getCurrentChannel()->id,
        ]);

        $parsedUrl = parse_url(config('app.url'));

        $urlPath = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';

        // dd($urlPath);

        return view('pwa::shop.home.index', compact('customizations', 'urlPath'));
        // return view('pwa::master', compact('customizations', 'urlPath'));
    }
}
