@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
])
@php
    $parsedUrl = parse_url(config('app.url'));
    $urlPath = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">
    <head>

        <title>
            {{ core()->getConfigData('pwa.settings.seo.seo_title') ?? 'PWA'  }}
        </title>
        {{-- <title>{{ $title ?? '' }}</title> --}}

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="base-url" content="{{ url()->to('/') }}">
        <meta name="currency-code" content="{{ core()->getCurrentCurrencyCode() }}">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

        {{-- pwa  --}}
        <meta name="theme-color" content="{{ core()->getConfigData('pwa.settings.general.theme_color') ?? '#0041ff'  }}">
        <meta name="description" content="{{ core()->getConfigData('pwa.settings.seo.seo_description') ?? 'This is a PWA app'  }}" >

        @if (core()->getConfigData('pwa.settings.seo.seo_author'))
        <meta name="author" content="{{ core()->getConfigData('pwa.settings.seo.seo_author')  }}" >
        @endif

        @if (core()->getConfigData('pwa.settings.seo.seo_keywords'))
        <meta name="keywords" content="{{ core()->getConfigData('pwa.settings.seo.seo_keywords')  }}" >
        @endif

        <meta name="apple-mobile-web-app-capable" content="yes">

        @stack('meta')

        {{-- <link
            rel="icon"
            sizes="16x16"
            href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}"
        /> --}}
        {{-- pwa icons --}}
        <link rel="icon" sizes="48x48" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.small')) : asset('themes/pwa/default/build/assets/images/48x48.png') }}" />
        <link rel="icon" sizes="96x96" href="{{ core()->getConfigData('pwa.settings.media.medium') ? Storage::url(core()->getConfigData('pwa.settings.media.medium')) : asset('themes/pwa/default/build/assets/images/96x96.png')  }}">
        <link rel="icon" sizes="144x144" href="{{ core()->getConfigData('pwa.settings.media.large') ? Storage::url(core()->getConfigData('pwa.settings.media.large')) : asset('themes/pwa/default/build/assets/images/144x144.png')  }}">
        <link rel="icon" sizes="196x196" href="{{ core()->getConfigData('pwa.settings.media.extra_large') ? Storage::url(core()->getConfigData('pwa.settings.media.extra_large')) : asset('themes/pwa/default/build/assets/images/196x196.png')  }}">

        {{-- icons for IOS devices --}}
        <link rel="apple-touch-icon" sizes="48x48" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.small')) : asset('themes/pwa/default/build/assets/images/48x48.png')  }}">
        <link rel="apple-touch-icon" sizes="96x96" href="{{ core()->getConfigData('pwa.settings.media.medium') ? Storage::url(core()->getConfigData('pwa.settings.media.medium')) : asset('themes/pwa/default/build/assets/images/96x96.png')  }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ core()->getConfigData('pwa.settings.media.large') ? Storage::url(core()->getConfigData('pwa.settings.media.large')) : asset('themes/pwa/default/build/assets/images/144x144.png')  }}">
        <link rel="apple-touch-icon" sizes="196x196" href="{{ core()->getConfigData('pwa.settings.media.extra_large') ? Storage::url(core()->getConfigData('pwa.settings.media.extra_large')) : asset('themes/pwa/default/build/assets/images/196x196.png')  }}">

        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

        @bagistoVite(['src/Resources/assets/css/app.css'], 'pwa')

        @stack('styles')

        <style>
            {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
        </style>

        {!! view_render_event('bagisto.shop.pwa.layout.head') !!}
    </head>

    <body>
        {!! view_render_event('bagisto.shop.pwa.layout.body.before') !!}

        <a href="#main" class="skip-to-main-content-link">Skip to main content</a>

        <div id="app">
            <!-- Flash Message Blade Component -->
            <x-shop-pwa::flash-group />

            <!-- Confirm Modal Blade Component -->
            <x-shop-pwa::modal.confirm />

            <!-- Page Header Blade Component -->
            @if ($hasHeader)
                <x-shop-pwa::layouts.header />
            @endif

            {!! view_render_event('bagisto.shop.pwa.layout.content.before') !!}

            <!-- Page Content Blade Component -->
            <main id="main" class="bg-white">
                {{ $slot }}
            </main>

            {!! view_render_event('bagisto.shop.pwa.layout.content.after') !!}


            <!-- Page Services Blade Component -->
            @if ($hasFeature)
                <x-shop-pwa::layouts.services />
            @endif

            <!-- Page Footer Blade Component -->
            @if ($hasFooter)
                <x-shop-pwa::layouts.footer />
            @endif
        </div>
        <script type="text/javascript">
            window.channel = @json(new \Webkul\API\Http\Resources\Core\Channel(core()->getCurrentChannel()));
            window.config = {
                app_short_name: "{{ core()->getConfigData('pwa.settings.general.short_name') }}",
                app_base_url: "{{ config('app.url') }}",
                url_path: "{{ $urlPath }}",
                prefix: "{{ $urlPath }}" + '/' + "{{ request()->route()->getName() == 'pwa.home' ? 'pwa' : 'mobile' }}",
                currencies: @json(core()->getCurrentChannel()->currencies),
                currentCurrency: @json(core()->getCurrentCurrency()),
                locales: @json(core()->getCurrentChannel()->locales),
                currentLocale: @json(core()->getCurrentLocale())
            };
        </script>

        {!! view_render_event('bagisto.shop.pwa.layout.body.after') !!}

        @stack('scripts')

        <script>
            if ('serviceWorker' in navigator ) {

                window.addEventListener('load', function() {
                    navigator.serviceWorker.register("{{ asset('service-worker.js') }}")
                        .then(function(registration) {
                            console.log('ServiceWorker registration successful with scope: ', registration.scope);

                            let deferredPrompt;

                            window.addEventListener('beforeinstallprompt', (e) => {
                                // Stash the event so it can be triggered later.
                                deferredPrompt = e;
                                // Update UI to notify the user they can add to home screen
                            });

                        }, function(err) {
                            console.log('ServiceWorker registration failed: ', err);
                        });
                });
            }
        </script>

        <script type="text/javascript">
            {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
        </script>
    </body>
</html>
