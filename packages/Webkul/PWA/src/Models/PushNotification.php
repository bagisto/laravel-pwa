<?php

namespace Webkul\PWA\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $table = "push_notifications";

    public $primaryKey = 'id';

    protected $fillable = ['title','description','targeturl','imageurl'];
}
