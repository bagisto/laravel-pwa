<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="{{ core()->getConfigData('pwa.settings.general.theme_color') ?? '#0041ff'  }}">
        <meta name="description" content="{{ core()->getConfigData('pwa.settings.seo.seo_description') ?? 'This is a PWA app'  }}" > <!-- this line is to meet the requirment of lighthouse extension. -->

        @if (core()->getConfigData('pwa.settings.seo.seo_author'))
        <meta name="author" content="{{ core()->getConfigData('pwa.settings.seo.seo_author')  }}" >
        @endif

        @if (core()->getConfigData('pwa.settings.seo.seo_keywords'))
        <meta name="keywords" content="{{ core()->getConfigData('pwa.settings.seo.seo_keywords')  }}" >
        @endif

        <link rel="stylesheet" href="{{ asset('vendor/webkul/pwa/assets/css/pwa.css?v=' . strtotime("now")) }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

        <link rel="icon" sizes="48x48" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.small')) : asset('vendor/webkul/pwa/assets/images/48x48.png') }}" />
        <link rel="icon" sizes="96x96" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.medium')) : asset('vendor/webkul/pwa/assets/images/96x96.png')  }}">
        <link rel="icon" sizes="144x144" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.large')) : asset('vendor/webkul/pwa/assets/images/144x144.png')  }}">
        <link rel="icon" sizes="196x196" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.extra_large')) : asset('vendor/webkul/pwa/assets/images/196x196.png')  }}">

        {{-- icons for IOS devices --}}
        <link rel="apple-touch-icon" sizes="48x48" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.small')) : asset('vendor/webkul/pwa/assets/images/48x48.png')  }}">
        <link rel="apple-touch-icon" sizes="96x96" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.medium')) : asset('vendor/webkul/pwa/assets/images/96x96.png')  }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.large')) : asset('vendor/webkul/pwa/assets/images/144x144.png')  }}">
        <link rel="apple-touch-icon" sizes="196x196" href="{{ core()->getConfigData('pwa.settings.media.small') ? Storage::url(core()->getConfigData('pwa.settings.media.extra_large')) : asset('vendor/webkul/pwa/assets/images/196x196.png')  }}">
        <meta name="apple-mobile-web-app-capable" content="yes">

        <span class="phpdebugbar-text-muted">
        <samp data-depth="1" class="sf-dump-compact">

        {!! view_render_event('bagisto.pwa.layout.head') !!}
        <title>
            {{ core()->getConfigData('pwa.settings.seo.seo_title') ?? 'PWA'  }}
        </title>
    </head>

    <body @if (app()->getLocale() == 'ar') class="rtl" dir="rtl" @endif style="scroll-behavior: smooth;">

        {!! view_render_event('bagisto.pwa.layout.body.before') !!}

        <div id="app">
            <app></app>
        </div>

        <script type="text/javascript">
            window.channel = @json(new \Webkul\API\Http\Resources\Core\Channel(core()->getCurrentChannel()));
            window.config = {
                url_path        : "{{ $urlPath }}",
                app_base_url    : "{{ config('app.url') }}",
                app_short_name  : "{{ core()->getConfigData('pwa.settings.general.short_name') }}",
                prefix          : "{{ $urlPath }}" + '/' + "{{ request()->route()->getName() == 'pwa.home' ? 'pwa' : 'mobile' }}",
                currentCurrency : @json(core()->getCurrentCurrency()),
                currencies      : @json(core()->getCurrentChannel()->currencies),
                locales         : @json(core()->getCurrentChannel()->locales),
                currentLocale   : @json(core()->getCurrentLocale())
            };
        </script>

        <script type="text/javascript" src="{{ asset('vendor/webkul/pwa/assets/js/app.js?v=' . strtotime("now")) }}"></script>

        @stack('scripts')

        {!! view_render_event('bagisto.pwa.layout.body.after') !!}

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
    </body>

</html>