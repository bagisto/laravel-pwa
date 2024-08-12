<?php

namespace Webkul\PWA\Http\Controllers;

use Detection\MobileDetect;
use WhichBrowser\Parser;

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

        return redirect()->route('shop.home.index');
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

        $result = new Parser(request()->header('User-Agent'));
        $device = $result->device;

        return view('pwa::master', compact('urlPath', 'device'));
    }
}
