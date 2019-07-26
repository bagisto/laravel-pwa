<?php

namespace Webkul\PWA\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\PWA\Models\PushNotification as PushNotification;
use Webkul\PWA\Repositories\PushNotificationRepository as PushNotificationRepository;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

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
        // $title = 'aayush';
        // $body = 'aayush bhatt PWA notification';
        // $notification = array(
        //     'title' => $title ,
        //     'body' => $body,
        //     'sound' => 'default',
        //     'badge' => '1'
        // );

        // $arrayToSend = array('notification' => $notification,'priority'=>'high');
        // $json = json_encode($arrayToSend);
        // //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        // $server_key = 'AAAAZ5GsN90:APA91bESSlGd3V9a2BFm5lRP4-IM5qd0_pEpYCjAyeK94KqLmjtgWPNY1p7B-9uBdiyb3IPluH26PFgmVVAsBYPTXL6yfAJ5dM7gFYXu8R8QAu_PHDVGQtSaNA35ORhyvndWhkzVpXOP';
        // $headers = array();
        // $headers[] = 'Content-Type: application/json';
        // $headers[] = 'Authorization: key='. $server_key;

        // //FCM API end-point
        // $url = 'https://fcm.googleapis.com/fcm/send';

        // //CURL request to route notification to FCM connection server (provided by Google)
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $headers);
        // $result = curl_exec($ch);

        // if ($result === FALSE) {
        //     die('Oops! FCM Send Error: ' . curl_error($ch));
        // }
        // curl_close($ch);


        // // Brozot/laravel-fcm wala code
        //     $optionBuilder = new OptionsBuilder();
        //     $optionBuilder->setTimeToLive(60*20);

        //     $notificationBuilder = new PayloadNotificationBuilder('my title');
        //     $notificationBuilder->setBody('Hello world')
        //                         ->setSound('default');

        //     $dataBuilder = new PayloadDataBuilder();
        //     $dataBuilder->addData(['a_data' => 'my_data']);

        //     $option = $optionBuilder->build();
        //     $notification = $notificationBuilder->build();
        //     $data = $dataBuilder->build();

        //     $token = "a_registration_from_your_database";

        //     $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        //     $downstreamResponse->numberSuccess();
        //     $downstreamResponse->numberFailure();
        //     $downstreamResponse->numberModification();

        //     //return Array - you must remove all this tokens in your database
        //     $downstreamResponse->tokensToDelete();

        //     //return Array (key : oldToken, value : new token - you must change the token in your database )
        //     $downstreamResponse->tokensToModify();

        //     //return Array - you should try to resend the message to the tokens in the array
        //     $downstreamResponse->tokensToRetry();

        //     // return Array (key:token, value:errror) - in production you should remove from your database the tokens
        $pushnotification = $this->PushNotificationRepository->find($id);

        dd(json_encode($pushnotification));
    }

}
