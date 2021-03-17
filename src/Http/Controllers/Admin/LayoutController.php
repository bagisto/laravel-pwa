<?php

namespace Webkul\PWA\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\PWA\Http\Controllers\Controller;
use Webkul\PWA\Repositories\PWALayoutRepository;

/**
 * Push Notification controller
 *
 * @author    Shubham Mehrotra <shubhammehrotra.symfony@webkul.com>@shubh-webkul
 * @copyright 2020 Webkul Software Pvt Ltd (http://www.webkul.com)
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
     * PWALayoutRepository object
     *
     * @var array
     */
    protected $pwaLayoutRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\CoreConfigRepository  $coreConfig
     * @return void
     */
    public function __construct(
        PWALayoutRepository $pwaLayoutRepository
    ) {
        $this->_config = request('_config');

        $this->pwaLayoutRepository = $pwaLayoutRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view'], [
            'layout' => $this->pwaLayoutRepository->first()
        ]);
    }
    
    /**
     * Store a newly created resource or update the existing one in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
                'home_page_content' => trim(request()->get('home_page_content'))
            ], $existing->id);
        } else {
            $this->pwaLayoutRepository->create([
                'home_page_content' => trim(request()->get('home_page_content'))
            ]);
        }
        
         // flash message
         session()->flash('success', trans('admin::app.response.update-success', ['name' => trans('pwa::app.admin.layouts.index')]));

         return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pushnotification = $this->pushNotificationRepository->findOrFail($id);
        return view($this->_config['view'], compact('pushnotification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate(request(), [
                'title' => 'required',
                'description' => 'required',
                'targeturl' => 'required',
                'image.*' => 'mimes:jpeg,jpg,bmp,png'
            ]);

            $this->pushNotificationRepository->update(request()->all(), $id);

            session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Push Notification']));

            return redirect()->route($this->_config['redirect']);
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pushnotification = $this->pushNotificationRepository->findOrFail($id);

        $this->pushNotificationRepository->delete($id);

        session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Push Notification']));
        
        return redirect()->back();
    }

    public function pushtofirebase($id) // send push notification to multiple devices
    {
        $topic = core()->getConfigData('pwa.settings.push-notification.topic');
        $server_key = core()->getConfigData('pwa.settings.push-notification.api-key');

        if ( $topic && $server_key ) {
            $pushnotification = $this->pushNotificationRepository->findOrFail($id);
            
            $title = $pushnotification->title;
            $body = $pushnotification->description;
            $icon = asset('/storage/' . $pushnotification->imageurl);
            $targeturl = $pushnotification->targeturl;

            $url = 'https://fcm.googleapis.com/fcm/send';

            $fields = array(
                'to' => '/topics/' . $topic,
                'data' => [
                    'body' => $body,
                    'title' => $title,
                    'icon' => $icon,
                    'click_action' => $targeturl
                ]
            );

            $headers = array(
                'Content-Type:application/json',
                'Authorization:key=' . $server_key,
            );

            // Open connection
            $ch = curl_init();

            curl_setopt( $ch, CURLOPT_URL, $url );

            curl_setopt( $ch, CURLOPT_POST, true );

            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            // Disabling SSL Certificate support temporarly
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );

            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
            // Execute post
            $result = curl_exec( $ch );

            if ( $result === false ) {
                session()->flash('error', 'Curl failed: ' . curl_error($ch));
            } else {
                session()->flash('success', trans('pwa::app.admin.push-notification.success-notification'));
            }

            curl_close( $ch );

            // Close connection
            return redirect()->back();
        }
    }
}