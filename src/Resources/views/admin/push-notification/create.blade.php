<x-admin::layouts>

    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.catalog.categories.create.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.pwa.notification.create.before') !!}

    <!-- Category Create Form -->
    <x-admin::form
        :action="route('admin.pwa.pushnotification.store')"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.admin.pwa.notification.create.create_form_controls.before') !!}

        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('pwa::app.admin.push-notification.create-notification')
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
                        @lang('pwa::app.admin.push-notification.general')
                    </p>

                    <!-- Title -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('pwa::app.admin.push-notification.label-title')
                        </x-admin::form.control-group.label>

                        <v-field
                            type="text"
                            name="title"
                            rules="required"
                            value="{{ old('title') }}"
                            v-slot="{ field }"
                            label="{{ trans('pwa::app.admin.push-notification.label-title') }}"
                        >
                            <input
                                type="text"
                                id="name"
                                :class="[errors['{{ 'name' }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                name="name"
                                v-bind="field"
                                placeholder="{{ trans('pwa::app.admin.push-notification.label-title') }}"
                                v-slugify-target:slug="setValues"
                            >
                        </v-field>

                        <x-admin::form.control-group.error control-name="title" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('pwa::app.admin.push-notification.label-title')
                        </x-admin::form.control-group.label>

                        <v-field
                            type="url"
                            name="targeturl"
                            rules="required"
                            value="{{ old('targeturl') }}"
                            v-slot="{ field }"
                            label="{{ trans('pwa::app.admin.push-notification.target-url') }}"
                        >
                            <input
                                type="text"
                                id="targeturl"
                                :class="[errors['{{ 'name' }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                name="targeturl"
                                v-bind="field"
                                placeholder="{{ trans('pwa::app.admin.push-notification.target-url') }}"
                                v-slugify-target:slug="setValues"
                            >
                        </v-field>

                        <x-admin::form.control-group.error control-name="targeturl" />
                    </x-admin::form.control-group>

                    <!-- Description -->
                    <v-description v-slot="{ isDescriptionRequired }">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label ::class="{ 'required' : isDescriptionRequired}">
                                @lang('pwa::app.admin.push-notification.description')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                id="description"
                                class="description"
                                name="description"
                                ::rules="{ 'required' : isDescriptionRequired}"
                                :value="old('description')"
                                :label="trans('pwa::app.admin.push-notification.description')"
                                :tinymce="true"
                                :prompt="core()->getConfigData('general.magic_ai.content_generation.category_description_prompt')"
                            />

                            <x-admin::form.control-group.error control-name="description" />
                        </x-admin::form.control-group>
                    </v-description>
                </div>

                {!! view_render_event('bagisto.admin.pwa.notification.create.card.general.after') !!}
            </div>

            <!-- Right Section -->
            <div class="flex flex-col gap-2 w-[360px] max-w-full rounded box-shadow">
                <!-- Icon -->
                {!! view_render_event('bagisto.admin.pwa.notification.create.card.log.before') !!}

                        <p class="p-2.5 text-base text-gray-800 dark:text-white font-semibold">
                           @lang('pwa::app.admin.push-notification.icon')
                        </p>

                        <div class="p-2.5 flex flex-col gap-2 w-full">
                            <x-admin::media.images name="image" />
                        </div>

                {!! view_render_event('bagisto.admin.pwa.notification.create.card.logo.after') !!}

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
