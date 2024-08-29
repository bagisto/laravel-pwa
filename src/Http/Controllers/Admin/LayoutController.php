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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected PWALayoutRepository $pwaLayoutRepository,
    ) {}

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

    /**
     * Store a newly created resource or update the existing one in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // $this->validate(request(), [
        //     'home_page_content' => 'required',
        // ]);

        $existing = $this->pwaLayoutRepository->first();

        if ($existing) {
            $this->pwaLayoutRepository->update([
                'home_page_content' => trim(request()->get('home_page_content')),
            ], $existing->id);
        } else {
            $this->pwaLayoutRepository->create([
                'home_page_content' => trim(request()->get('home_page_content')),
            ]);
        }

        session()->flash('success', trans('pwa::app.admin.layouts.update-success'));

        return redirect()->route('admin.pwa.layout');
    }
}
