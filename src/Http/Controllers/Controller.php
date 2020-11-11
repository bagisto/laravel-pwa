<?php

namespace Webkul\PWA\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public function returnView()
    {
        return view($this->_config['view']);
    }
}
