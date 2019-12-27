<?php

namespace Webkul\PWA\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

/**
 * PWA service provider
 *
 * @author    Vivek Sharma <viveksh047@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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

        $this->app->register(ModuleServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/pwa/assets'),
            __DIR__ . '/../../publishable/pwa' => public_path(),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'pwa');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'pwa');

        Event::listen('core.configuration.save.after', 'Webkul\PWA\Listeners\CoreConfig@generateManifestFile');

        $this->publishes([
            __DIR__ . '/../Resources/views/paypal/standard-redirect.blade.php' => resource_path('views/vendor/paypal/standard-redirect.blade.php'),
        ]);
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
    }
}
