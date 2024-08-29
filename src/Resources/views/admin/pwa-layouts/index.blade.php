<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.catalog.categories.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.pwa.notification.create.before') !!}

    <!-- Category Create Form -->
    <x-admin::form
        enctype="multipart/form-data"
        method="POST"
    >
        {!! view_render_event('bagisto.admin.pwa.notification.create.create_form_controls.before') !!}
        @csrf
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('pwa::app.admin.layouts.title')
            </p>

            <div class="flex gap-x-2.5 items-center">
                <!-- Cancel Button -->
                <a
                    href="{{ route('admin.pwa.pushnotification.index') }}"
                    class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white"
                >
                    @lang('pwa::app.admin.push-notification.back-btn')
                </a>

                <!-- Save Button -->
                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('pwa::app.admin.push-notification.btn-save')
                </button>
            </div>
        </div>

        <!-- Full Pannel -->
        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">

            <!-- Left Section -->
            <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">

                {!! view_render_event('bagisto.admin.pwa.notification.create.card.general.before') !!}

                <!-- General -->
                <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
                        @lang('pwa::app.admin.layouts.sub-title')
                    </p>

                    <!-- Description -->
                        <x-admin::form.control-group>

                            <x-admin::form.control-group.control
                                type="textarea"
                                id="description"
                                {{-- class="description" --}}
                                name="home_page_content"
                                :value="isset($layout->home_page_content) ? $layout->home_page_content : ''"
                                :label="trans('pwa::app.admin.push-notification.description')"
                                {{-- :tinymce="true" --}}
                            />

                            <x-admin::form.control-group.error control-name="description" />
                        </x-admin::form.control-group>
                </div>

                {!! view_render_event('bagisto.admin.pwa.notification.create.card.general.after') !!}
            </div>
        </div>

        {!! view_render_event('bagisto.admin.pwa.notification.create.create_form_controls.after') !!}

    </x-admin::form>

    {!! view_render_event('bagisto.admin.pwa.notification.create.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-description-template">
            <div>
                <slot :is-description-required="isDescriptionRequired"></slot>
            </div>
        </script>

        <script type="module">
            app.component('v-description', {
                template: '#v-description-template',

                data() {
                    return {
                        isDescriptionRequired: true,

                        displayMode: "{{ old('display_mode') ?? 'products_and_description' }}",
                    };
                },

                mounted() {
                    this.isDescriptionRequired = this.displayMode !== 'products_only';

                    this.$nextTick(() => {
                        document.querySelector('#display_mode').addEventListener('change', (e) => {
                            this.isDescriptionRequired = e.target.value !== 'products_only';
                        });
                    });
                },
            });
        </script>
    @endPushOnce

</x-admin::layouts>
