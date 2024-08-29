<?php

namespace Webkul\PWA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\PWA\Contracts\PushNotification as PushNotificationContract;

class PushNotification extends Model implements PushNotificationContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'push_notifications';

    /**
     * The primary key associated with the table.
     */
    public $primaryKey = 'id';

    /**
     * Add fillable property to the model.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'targeturl',
        'imageurl'
    ];

    /**
     * Add the translateable attribute.
     *
     * @var array
     */
    public $translatedAttributes = [
        'description',
    ];

    /**
     * Get image url for the notification image.
     */
    public function imageurl_url()
    {
        if (! $this->imageurl) {
            return;
        }

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
