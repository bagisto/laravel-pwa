<?php

namespace Webkul\PWA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\PWA\Contracts\PushNotification as PushNotificationContract;
use Storage;

class PushNotification extends Model implements PushNotificationContract
{
    protected $table = 'push_notifications';

    public $primaryKey = 'id';

    protected $fillable = ['title', 'description', 'targeturl', 'imageurl'];

    /**
     * Get image url for the notification image.
     */
    public function imageurl_url()
    {   
        if (! $this->imageurl)
            return;

        return Storage::url($this->imageurl);
    }

     /**
     * Get image url for the category image.
     */
    public function getImageUrlUrlAttribute()
    {
        return $this->imageurl_url();
    }
}