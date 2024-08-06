<x-admin::layouts>
    <x-slot:title>
       {{ __('pwa::app.admin.push-notification.title') }}
    </x-slot>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
           {{ __('pwa::app.admin.push-notification.title') }}
        </p>

        <div class="flex gap-x-2.5 items-center">
            {!! view_render_event('bagisto.admin.pwa.push-notification.index.create-button.before') !!}

                <a href="{{ route('admin.pwa.pushnotification.create') }}">
                    <div class="primary-button">
                         {{ __('pwa::app.admin.push-notification.create-notification') }}
                    </div>
                </a>

            {!! view_render_event('bagisto.admin.pwa.push-notification.index.create-button.after') !!}
        </div>
    </div>

    {!! view_render_event('bagisto.admin.pwa.push-notification.list.before') !!}

    <x-admin::datagrid src="{{ route('admin.pwa.pushnotification.index') }}" />

    {!! view_render_event('bagisto.admin.pwa.push-notification.list.after') !!}

</x-admin::layouts>
