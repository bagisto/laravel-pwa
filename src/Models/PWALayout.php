<?php

namespace Webkul\PWA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\PWA\Contracts\PWALayout as PWALayoutContract;

class PWALayout extends Model implements PWALayoutContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pwa_layout';

    /**
     * Add fillable property to the model.
     *
     * @var array
     */
    protected $fillable = [
        'home_page_content',
    ];
}
