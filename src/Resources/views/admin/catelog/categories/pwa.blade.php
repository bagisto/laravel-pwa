<div class="bg-white dark:bg-gray-900 rounded box-shadow">
    <x-admin::accordion>
        <x-slot:header>
            <p class="p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                @lang('pwa::app.admin.system.pwa')
            </p>
        </x-slot>
        <x-slot:content>
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="text-gray-800 dark:text-white font-medium">
                    @lang('pwa::app.admin.system.add_in_pwa')
                </x-admin::form.control-group.label>

                @php
                $is_add_in_pwa = old('category_product_in_pwa');

                if (isset($category)) {
                    $is_add_in_pwa = old('category_product_in_pwa') ?: $category->category_product_in_pwa;
                }
                @endphp

                <x-admin::form.control-group.control
                    type="switch"
                    class="cursor-pointer"
                    name="category_product_in_pwa"
                    value="1"
                    :label="trans('pwa::app.admin.system.add_in_pwa')"
                    :checked="(boolean) $is_add_in_pwa"
                />
            </x-admin::form.control-group>
        </x-slot>
    </x-admin::accordion>
</div>
