<?php

namespace Webkul\PWA\Http\Controllers;

use Detection\MobileDetect;

/**
 * Home page controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class SinglePageController extends Controller
{
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
        if (! core()->getConfigData('pwa.settings.general.status')) {
            session()->flash('warning', trans('pwa::app.shop.home.enable-pwa-status'));

            return redirect()->route('shop.home.index');
        }

        $parsedUrl = parse_url(config('app.url'));

        $urlPath = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';

        return view('pwa::master', compact('urlPath'));
    }
}
