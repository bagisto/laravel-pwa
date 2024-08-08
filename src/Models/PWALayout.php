<?php

namespace Webkul\PWA\Models;

use Storage;
use Illuminate\Database\Eloquent\Model;
use Webkul\PWA\Contracts\PWALayout as PWALayoutContract;

class PWALayout extends Model implements PWALayoutContract
{
    protected $guarded = [];
    protected $table = 'pwa_layout';
}
