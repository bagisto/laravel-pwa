<?php

namespace Webkul\PWA\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class PWAServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/pwa/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'pwa');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}
