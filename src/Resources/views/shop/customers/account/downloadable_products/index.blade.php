<x-shop-pwa::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.downloadable-products.name')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop-pwa::breadcrumbs name="downloadable-products" />
    @endSection

    <div class="flex-auto">
        <div class="max-md:max-w-full">
            <h2 class="text-2xl font-medium">
                @lang('shop::app.customers.account.downloadable-products.name')
            </h2>

            {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.before') !!}

            <x-shop-pwa::datagrid :src="route('shop.customers.account.downloadable_products.index')" />

            {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.after') !!}

        </div>
    </div>
</x-shop-pwa::layouts.account>
