<?php

namespace Webkul\PWA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Container\Container as App;

/**
 * PWALayoutRepository Reposotory
 *
 */
class PWALayoutRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\PWA\Contracts\PWALayout';
    }
}