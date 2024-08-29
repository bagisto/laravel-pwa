<?php

namespace Webkul\PWA\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;

/**
 * PushNotificationRepository Reposotory
 */
class PushNotificationRepository extends Repository
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\PWA\Contracts\PushNotification';
    }

    /**
     * @param  array  $data
     * @return mixed
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->_config = request('_config');
    }

    /**
     * Save notification.
     *
     * @param array $data notification data.
     */
    public function create(array $data)
    {
        $pushnotification = $this->model->create($data);

        $this->uploadImages($data, $pushnotification);

        return $pushnotification;
    }

    /**
     * Update Notification.
     *
     * @param array $data notification data.
     * @param int   $id notification id.
     */
    public function update(array $data, $id)
    {
        $pushnotification = $this->find($id);

        $pushnotification->update($data);

        $this->uploadImages($data, $pushnotification);

        return $pushnotification;
    }

    /**
     * Delete notification.
     *
     * @param int $id notification id.
     */
    public function delete($id)
    {
        parent::delete($id);
    }

    /**
     * @param  array  $data
     * @param  mixed  $category
     * @return void
     */
    public function uploadImages($data, $notification, $type = 'image')
    {
        if (isset($data[$type])) {
            $request = request();

            foreach ($data[$type] as $imageId => $image) {
                $file = $type.'.'.$imageId;
                $dir = 'notification/'.$notification->id;

                if ($request->hasFile($file)) {
                    if ($notification->imageurl) {
                        Storage::delete($notification->imageurl);
                    }

                    $notification->imageurl = $request->file($file)->store($dir);
                    $notification->save();
                }
            }
        } else {
            if ($notification->imageurl) {
                Storage::delete($notification->imageurl);
            }

            $notification->imageurl = null;
            $notification->save();
        }
    }
}
