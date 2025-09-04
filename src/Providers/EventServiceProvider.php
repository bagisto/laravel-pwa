<?php

namespace Webkul\PWA\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.shop.layout.head.before', 'Webkul\PWA\Listeners\PWAListeners@redirectToPWA');

        Event::listen('core.configuration.save.after', 'Webkul\PWA\Listeners\CoreConfig@generateManifestFile');

        /**
         * Add css to the admin end using listener.
         */
        Event::listen('bagisto.admin.layout.head.before', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('pwa::admin.layouts.style');
        });

        /**
         * Add new field in category create and update form.
         */
        Event::listen(
            [
                'bagisto.admin.catalog.categories.create.card.accordion.settings.after',
                'bagisto.admin.catalog.categories.edit.card.accordion.settings.after',
            ],
            function ($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'pwa::admin.catalog.categories.pwa'
                );
            }
        );

        /**
         * Add new filed in array when save category.
         */
        Event::listen([
            'catalog.category.create.after',
            'catalog.category.update.after',
        ], 'Webkul\PWA\Helpers\AdminHelper@storePwaStatusInCategory');
    }
}
