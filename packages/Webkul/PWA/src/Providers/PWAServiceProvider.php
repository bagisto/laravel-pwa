<?php

namespace Webkul\PWA\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;

class PWAServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/pwa/assets'),
            __DIR__ . '/../../publishable/pwa' => public_path(),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'pwa');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'pwa');

        Event::listen('core.configuration.save.after', 'Webkul\PWA\Listeners\CoreConfig@generateManifestFile');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );

        // $this->mergeConfigFrom(
        //     dirname(__DIR__) . '/Config/acl.php', 'acl'
        //    );
    }
}
