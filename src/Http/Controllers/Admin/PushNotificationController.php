<?php

namespace Webkul\PWA\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Webkul\PWA\DataGrids\PushNotificationDataGrid;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected PushNotificationRepository $pushNotificationRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(PushNotificationDataGrid::class)->toJson();
        }

        return view('pwa::admin.push-notification.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pwa::admin.push-notification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'title'       => 'required',
            'description' => 'required',
            'targeturl'   => 'required',
            'image.*'     => 'mimes:jpeg,jpg,bmp,png',
        ]);

        $this->pushNotificationRepository->create(request()->all());

        session()->flash('success', trans('pwa::app.admin.push-notification.create-success'));

        return redirect()->route('admin.pwa.pushnotification.index');
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

        return view('pwa::admin.push-notification.edit', compact('pushnotification'));
    }

    /**
     * Update the specified resource in storage.
     *
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
                'image.*'       => 'mimes:jpeg,jpg,bmp,png',
            ]);

            $this->pushNotificationRepository->update(request()->all(), $id);

            session()->flash('success', trans('pwa::app.admin.notification.update-success', ['name' => trans('pwa::app.admin.layouts.push-notification')]));

            return redirect()->route('admin.pwa.pushnotification.index');
        } catch (\Exception $e) {
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
        $this->pushNotificationRepository->delete($id);

        session()->flash('success', trans('pwa::app.admin.push-notification.delete-success'));

        return redirect()->back();
    }

    public function pushToFirebase($id)
    {
        $topic = core()->getConfigData('pwa.settings.push-notification.topic');
        $serverKey = core()->getConfigData('pwa.settings.push-notification.api-key');

        if ($topic && $serverKey) {
            $pushNotification = $this->pushNotificationRepository->findOrFail($id);

            $fcmUrl = 'https://fcm.googleapis.com/v1/messages:send';
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer {$serverKey}",
            ];

            $data = [
                'message' => [
                    'notification' => [
                        'title' => $pushNotification->title,
                        'body' => $pushNotification->description,
                        'icon' => asset('/storage/' . $pushNotification->imageurl),
                    ],
                    'data' => [
                        'click_action' => $pushNotification->targeturl,
                    ],
                ],
            ];

            $response = Http::withHeaders($headers)
                ->post($fcmUrl, json_encode($data));

            if (!$response->successful()) {
                session()->flash('error', trans('pwa::app.admin.push-to-firebase.invalid-credentials'));
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
