<?php

namespace Webkul\PWA\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

/**
 * Event service provider
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
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

        Event::listen('core.configuration.save.after', 'Webkul\PWA\Listeners\CoreConfig@generateManifestFile');
    }
}
