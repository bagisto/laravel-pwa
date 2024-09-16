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

    protected $fillable = [
        'home_page_content',
    ];
}
