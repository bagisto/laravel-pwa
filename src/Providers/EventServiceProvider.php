<?php

namespace Webkul\PWA\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

/**
 * Event service provider
 *
 * @author Webkul Software Pvt. Ltd. <support@webkul.com>
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('pwa::admin.layouts.style');
        });

        Event::listen('bagisto.shop.layout.head', 'Webkul\PWA\Listeners\PWAListeners@redirectToPWA');

        Event::listen('core.configuration.save.after', 'Webkul\PWA\Listeners\CoreConfig@generateManifestFile');

        Event::listen([
            'bagisto.admin.catalog.category.edit_form_accordian.general.controls.after',
            'bagisto.admin.catalog.category.create_form_accordian.general.controls.after',
        ], function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'pwa::admin.catelog.categories.pwa'
                );
            }
        );

        Event::listen([
            'catalog.category.create.after',
            'catalog.category.update.after',
        ], 'Webkul\PWA\Helpers\AdminHelper@storeCategoryIcon');
    }
}
