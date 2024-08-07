@php
    $parsedUrl = parse_url(config('app.url'));
    $urlPath = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
@endphp

<link rel="manifest" href="{{ asset('manifest.json') }}">
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

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
