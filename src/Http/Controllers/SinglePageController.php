<?php

namespace Webkul\PWA\Http\Controllers;

use WhichBrowser\Parser;

class SinglePageController extends Controller
{
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
