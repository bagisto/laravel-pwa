<?php

namespace Webkul\PWA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Container\Container as App;

/**
 * PushNotificationRepository Reposotory
 */
class PushNotificationRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\PWA\Contracts\PushNotification';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->_config = request('_config');
    }


    public function create(array $data)
    {
        $pushnotification = $this->model->create($data);

        $this->uploadImages($data, $pushnotification);
        
        return $pushnotification;
    }

    public function update(array $data, $id)
    {
        $pushnotification = $this->find($id);

        $pushnotification->update($data);

        $this->uploadImages($data, $pushnotification);

        return $pushnotification;
    }

    public function delete($id)
    {
        parent::delete($id);
    }

    /**
     * @param array $data
     * @param mixed $category
     * @return void
     */
    public function uploadImages($data, $notification, $type = "image")
    {
        if (isset($data[$type])) {
            $request = request();

            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'notification/' . $notification->id;

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