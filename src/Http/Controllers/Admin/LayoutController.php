<?php

namespace Webkul\PWA\Http\Controllers\Admin;

use Webkul\PWA\Http\Controllers\Controller;
use Webkul\PWA\Repositories\PWALayoutRepository;

/**
 * Push Notification controller
 */
class LayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected PWALayoutRepository $pwaLayoutRepository,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pwa::admin.pwa-layouts.index', [
            'layout' => $this->pwaLayoutRepository->first(),
        ]);
    }
}
