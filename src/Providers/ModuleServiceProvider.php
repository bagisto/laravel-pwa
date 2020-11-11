<?php

namespace Webkul\PWA\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\PWA\Models\PWALayout::class,
        \Webkul\PWA\Models\PushNotification::class,
    ];
}