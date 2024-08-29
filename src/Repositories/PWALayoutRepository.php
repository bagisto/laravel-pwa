<?php

namespace Webkul\PWA\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * PWALayoutRepository Reposotory
 */
class PWALayoutRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\PWA\Contracts\PWALayout';
    }
}
