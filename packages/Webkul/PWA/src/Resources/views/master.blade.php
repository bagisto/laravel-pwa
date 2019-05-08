<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
    <link rel="stylesheet" href="{{ asset('vendor/webkul/pwa/assets/css/pwa.css') }}">

    @if ($favicon = core()->getCurrentChannel()->favicon_url)
        <link rel="icon" sizes="16x16" href="{{ $favicon }}" />
    @else
        <link rel="icon" sizes="16x16" href="{{ bagisto_asset('images/favicon.ico') }}" />
    @endif

    @stack('css')

    {!! view_render_event('bagisto.pwa.layout.head') !!}

</head>

<body @if (app()->getLocale() == 'ar') class="rtl" @endif style="scroll-behavior: smooth;">

    {!! view_render_event('bagisto.pwa.layout.body.before') !!}

    <div id="app">
        <app></app>
    </div>

    <script type="text/javascript">
        window.channel = @json(core()->getCurrentChannel());
        window.config = {
            app_base_url: "{{ config('app.url') }}",
            currencies: @json(core()->getCurrentChannel()->currencies),
            currentCurrency: @json(core()->getCurrentCurrency()),
            locales: @json(core()->getCurrentChannel()->locales),
            currentLocale: @json(core()->getCurrentLocale())
        };
    </script>

    <script type="text/javascript" src="{{ asset('vendor/webkul/pwa/assets/js/app.js') }}"></script>

    @stack('scripts')

    {!! view_render_event('bagisto.pwa.layout.body.after') !!}

</body>

</html>