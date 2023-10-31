<?php

namespace Webkul\PWA\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
     * Create a new controller instance.
     *
     * @param  \Webkul\Core\Repositories\CoreConfigRepository  $coreConfig
     * @return void
     */
    public function __construct(protected PushNotificationRepository $pushNotificationRepository)
    {
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
                'title'         => 'required',
                'description'   => 'required',
                'targeturl'     => 'required',
                'image.*'       => 'mimes:jpeg,jpg,bmp,png'
            ]);

            $this->pushNotificationRepository->update(request()->all(), $id);

            session()->flash('success', trans('pwa::app.admin.notification.update-success', ['name' => trans('pwa::app.admin.layouts.push-notification')]));

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

        session()->flash('success', trans('pwa::app.admin.notification.delete-success', ['name' => 'Push Notification']));
        
        return redirect()->back();
    }

    public function pushtofirebase($id) // send push notification to multiple devices
    {
        $topic = core()->getConfigData('pwa.settings.push-notification.topic');

        $serverKey = core()->getConfigData('pwa.settings.push-notification.api-key');

        if ($topic && $serverKey) {
            $pushNotification = $this->pushNotificationRepository->findOrFail($id);

            $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$serverKey}",
                ])
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => "/topics/{$topic}",
                    'data' => [
                        'title'        => $pushNotification->title,
                        'body'         => $pushNotification->description,
                        'icon'         => asset('/storage/' . $pushNotification->imageurl),
                        'click_action' => $pushNotification->targeturl,
                    ],
                ]);
            
            if (! $response?->collect()->get('message_id')) {
                session()->flash('error', 'Invalid Credentials');
            } else {
                session()->flash('success', trans('pwa::app.admin.push-notification.success-notification'));
            }

            return redirect()->back();
        } else {
            session()->flash('error', trans('admin::app.users.users.login-error'));
            
            return redirect()->back();
        }
    }
}