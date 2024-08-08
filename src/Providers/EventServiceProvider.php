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
        Event::listen('bagisto.admin.layout.head', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('pwa::admin.layouts.style');
        });

        Event::listen('bagisto.shop.layout.head', 'Webkul\PWA\Listeners\PWAListeners@redirectToPWA');

        Event::listen('core.configuration.save.after', 'Webkul\PWA\Listeners\CoreConfig@generateManifestFile');

        // Event::listen('bagisto.shop.layout.head', function ($viewRenderEventManager) {
        //     $viewRenderEventManager->addTemplate('pwa::shop.desktop.head.index');
        // });

        // Add new field in category create and update form.
        Event::listen(
            [
                'bagisto.admin.catalog.categories.create.card.accordion.settings.after',
                'bagisto.admin.catalog.categories.edit.card.accordion.settings.after',
            ],
            function ($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'pwa::admin.catelog.categories.pwa'
                );
            }
        );

        // Add new filed in array when save category.
        Event::listen([
            'catalog.category.create.after',
            'catalog.category.update.after',
        ], 'Webkul\PWA\Helpers\AdminHelper@storePwaStatusInCategory');
    }
}
