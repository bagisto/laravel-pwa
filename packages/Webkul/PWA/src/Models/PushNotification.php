<?php

namespace Webkul\PWA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\PWA\Contracts\PushNotification as PushNotificationContract;

class PushNotification extends Model implements PushNotificationContract
{
    protected $table = 'push_notifications';

    public $primaryKey = 'id';

    protected $fillable = ['title','description','targeturl','imageurl'];
}
