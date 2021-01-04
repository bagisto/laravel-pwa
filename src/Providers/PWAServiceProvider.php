<?php

namespace Webkul\PWA\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Webkul\Checkout\Facades\Cart as CartFacade;

/**
 * PWA service provider
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
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

        $this->app->register(EventServiceProvider::class);

        $this->app->register(ModuleServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'pwa');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'pwa');

        $this->publishes([
            __DIR__ . '/../Resources/views/paypal/standard-redirect.blade.php'          => resource_path('views/vendor/paypal/standard-redirect.blade.php'),
            __DIR__ . '/../Resources/views/shop/customers/account/orders/pdf.blade.php' => resource_path('views/vendor/shop/customers/account/orders/pdf.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../../publishable/pwa'      => public_path(),
            __DIR__ . '/../../publishable/assets'   => public_path('vendor/webkul/pwa/assets'),
        ], 'public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerFacades();
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

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('cart', CartFacade::class);

        $this->app->singleton('cart', function () {
            return new Cart();
        });

        $this->app->bind('cart', 'Webkul\PWA\Cart');
    }
}
