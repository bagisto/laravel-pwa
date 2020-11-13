<?php

namespace Webkul\PWA\Listeners;

use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Core Config event handler
 *
 */
class PWAListeners
{
    public function redirectToPWA()
    {
        $agent = new Agent();

        if (core()->getConfigData('pwa.settings.general.redirect_to_pwa_if_mobile') == "1") {
            if (
                $agent->isMobile()
                && request()->url() == route('shop.home.index')
            ) {
                return redirect()->to('/mobile')->send();
            }
        }
        
    }
}
