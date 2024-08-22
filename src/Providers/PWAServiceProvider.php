<?php

namespace Webkul\PWA\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Webkul\PWA\Cart;
use Webkul\PWA\Facades\PwaFacades;

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
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->app->register(EventServiceProvider::class);

        $this->app->register(ModuleServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'pwa');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'pwa');

        /**
         * Component*
         */
        // Blade::anonymousComponentPath(__DIR__ . '/../Resources/views/shop/components', 'shop-pwa');

        // $this->publishes([
        //     __DIR__ . '/../Resources/views/paypal/standard-redirect.blade.php'          => resource_path('views/vendor/paypal/standard-redirect.blade.php'),
        //     __DIR__ . '/../Resources/views/shop/customers/account/orders/pdf.blade.php' => resource_path('views/vendor/shop/customers/account/orders/pdf.blade.php'),
        // ]);

        $this->publishes([
            __DIR__.'/../../publishable/pwa'      => public_path(),
            __DIR__.'/../../publishable/assets'   => public_path('themes/pwa/default/build/assets'),
        ], 'public');

        if (core()->getConfigData('pwa.settings.general.status')) {
            $this->mergeConfigFrom(
                dirname(__DIR__).'/Config/admin-menu.php',
                'menu.admin'
            );
        }
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
            dirname(__DIR__).'/Config/system.php',
            'core'
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

        $loader->alias('pwa_facade', PwaFacades::class);

        $this->app->singleton('pwa_facade', function () {
            return app()->make(PwaFacades::class);
        });

        $this->app->bind('Cart', 'Webkul\PWA\Cart');

        $this->app->bind(\Webkul\Checkout\Cart::class, Cart::class);
    }
}
