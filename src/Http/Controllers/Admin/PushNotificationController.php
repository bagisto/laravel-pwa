<?php

namespace Webkul\PWA\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\PWA\Http\Controllers\Controller;
use Webkul\PWA\Repositories\PushNotificationRepository as PushNotificationRepository;

/**
 * Push Notification controller
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * PushNotificationRepository object
     *
     * @var array
     */
    protected $pushNotificationRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\CoreConfigRepository  $coreConfig
     * @return void
     */
    public function __construct(PushNotificationRepository $pushNotificationRepository)
    {
        $this->pushNotificationRepository = $pushNotificationRepository;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'title' => 'required',
            'description' => 'required',
            'targeturl' => 'required',
            'image.*' => 'mimes:jpeg,jpg,bmp,png'
        ]);
        
        // call the repository
        $this->pushNotificationRepository->create(request()->all());

         // flash message
         session()->flash('success', trans('admin::app.response.create-success', ['name' => trans('pwa::app.admin.layouts.push-notification')]));

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
                'Authorization:key='.$server_key,
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
            curl_close( $ch );
            $resp = json_decode($result);
            if ( empty($resp->message_id) ) {
                session()->flash('error', 'Invalid Credentials');
            } else {
                session()->flash('success', trans('pwa::app.admin.push-notification.success-notification'));
            }

            // Close connection
            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.users.users.login-error'));
            
            return redirect()->back();
        }
    }
}