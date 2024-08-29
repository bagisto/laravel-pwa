<?php

namespace Webkul\PWA\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\PWA\Http\Controllers\Controller;
use Webkul\PWA\Repositories\PWALayoutRepository;

class LayoutController extends Controller
{
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
    public function get()
    {
        return response()->json([
            'data' => $this->pwaLayoutRepository->first(),
        ]);
    }

    /**
     * Store a newly created resource or update the existing one in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        // flash message
        session()->flash('success', trans('pwa::app.admin.layouts.update-success'));

        return redirect()->route('admin.pwa.layout');
    }
}
