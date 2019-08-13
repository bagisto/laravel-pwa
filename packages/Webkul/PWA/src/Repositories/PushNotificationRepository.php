<?php

namespace Webkul\PWA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Container\Container as App;

/**
 * Manufacturer Reposotory
 *
 * @author    Aayush Bhatt <aayush.bhatt172@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
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
        return 'Webkul\PWA\Models\PushNotification';
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
        return $pushnotification;
    }

    public function update(array $data, $id)
    {
        $pushnotification = $this->find($id);

        $pushnotification->update($data);

        return $pushnotification;
    }

    public function delete($id)
    {
        parent::delete($id);
    }
}