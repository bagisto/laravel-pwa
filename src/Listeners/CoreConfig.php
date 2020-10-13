<?php

namespace Webkul\PWA\Listeners;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * Core Config event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CoreConfig
{
    /**
     * Containes images sizes
     *
     */
    protected $sizes = [
        'small' => '48x48',
        'medium' => '96x96',
        'large' => '144x144',
        'extra_large' => '196x196',
    ];

    /**
     * Generate menifest.json file
     *
     */
    public function generateManifestFile()
    {
        if (strpos(request()->url(), 'configuration/pwa/settings') !== false) {
            $icons = [];

            foreach (['small', 'medium', 'large', 'extra_large'] as $size) {
                if (! core()->getConfigData('pwa.settings.media.' . $size))
                    continue;

                $icons[] = [
                    'src' => Storage::url(core()->getConfigData('pwa.settings.media.' . $size)),
                    'sizes' => $this->sizes[$size],
                    'type' => Storage::mimeType(core()->getConfigData('pwa.settings.media.' . $size))
                ];
            }

            if (! count($icons)) {
                foreach (['small', 'medium', 'large', 'extra_large'] as $size) {
                    $icons[] = [
                        'src' => 'vendor/webkul/pwa/assets/images/' . $this->sizes[$size] . '.png',
                        'sizes' => $this->sizes[$size],
                        'type' => 'image/png'
                    ];
                }
            }

            $manifest = [
                'name' => core()->getConfigData('pwa.settings.general.name') ?? 'Bagisto PWA App',
                'short_name' => core()->getConfigData('pwa.settings.general.short_name') ?? 'Bagisto',
                'start_url' => config('app.url'),
                'display' => 'standalone',
                'orientation' => 'any',
                'theme_color' => core()->getConfigData('pwa.settings.general.theme_color') ?? '#0041ff',
                'background_color' => core()->getConfigData('pwa.settings.general.background_color') ?? '#0041ff',
                'icons' => $icons
            ];

            File::put(public_path() . '/manifest.json', json_encode($manifest));
        }
    }
}
