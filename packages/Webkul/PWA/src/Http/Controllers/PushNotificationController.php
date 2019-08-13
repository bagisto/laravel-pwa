<?php

namespace Webkul\PWA\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\PWA\Models\PushNotification as PushNotification;
use Webkul\PWA\Repositories\PushNotificationRepository as PushNotificationRepository;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use DB;

class PushNotificationController extends Controller
{
    protected $_config;

    public function __construct(PushNotificationRepository $PushNotificationRepository)
    {
        $this->PushNotificationRepository = $PushNotificationRepository;

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

        // $this->validate($request, [
        //     'title'   => 'required',
        //     'description' => 'required',
        //     'targeturl' => 'required',
        //     'icon' => 'image|nullable|max:1999'
        // ]);

        // get all data from request
         $data = $request->all();

        // call the repository
        $pushnotification = $this->PushNotificationRepository->create($data);

         // flash message
         session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Push Notification']));

         return redirect()->route($this->_config['redirect']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $pushnotification = $this->PushNotificationRepository->find($id);

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

        $pushnotification = $this->PushNotificationRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Push Notification']));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pushnotification = $this->PushNotificationRepository->findOrFail($id);
        $this->PushNotificationRepository->delete($id);
        session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Push Notification']));
        return redirect()->back();
    }

    public function pushtofirebase($id) // send push notification to multiple devices
    {
        $pushnotification = \DB::table('push_notifications')->find($id);
        $title = $pushnotification->title;
        $body = $pushnotification->description;
        $badge = $pushnotification->imageurl;
        $targeturl = $pushnotification->targeturl;

        $url = 'https://fcm.googleapis.com/fcm/send';

            $topic = 'bagisto';

            $fields = array(
                'to' => '/topics/bagisto',
                'data' => [
                    'body' => $body,
                    'title' => $title,
                    'click_action' => $targeturl
                ]

            );

            $server_key = "AIzaSyBjbet3YzHEAp-YEkRN50zWx3asw0d07MA";

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

            if ( $result === false ) {
                die('Curl failed: ' . curl_error($ch));
            }

            curl_close( $ch );

            // Close connection
            return redirect()->back();
    }
}