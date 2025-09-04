<?php

namespace Webkul\PWA\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\PWA\Contracts\PWALayout;

class PWALayoutRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return PWALayout::class;
    }
}