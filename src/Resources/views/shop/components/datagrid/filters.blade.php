<div v-for="column in available.columns">
    <div v-if="column.filterable">
        <!-- Boolean -->
        <div v-if="column.type === 'boolean'">
            <div class="flex items-center justify-between">
                <p
                    class="text-sm font-medium leading-6 text-gray-800"
                    v-text="column.label"
                >
                </p>

                <div
                    class="flex items-center gap-x-1.5"
                    @click="removeAppliedColumnAllValues(column.index)"
                >
                    <p
                        class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                        v-if="hasAnyAppliedColumnValues(column.index)"
                    >
                        @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                    </p>
                </div>
            </div>

            <div class="mb-2 mt-1.5">
                <x-shop-pwa::dropdown>
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button class="flex justify-between items-center gap-4 w-full ltr:pl-4 rtl:pr-4 ltr:pr-3 rtl:pl-3 py-2 rounded-lg bg-white border border-[#E9E9E9] text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:ltr:pr-2.5 max-md:rtl:pl-2.5 max-md:ltr:pl-2.5 max-md:rtl:pr-2.5 max-md:border-0 max-md:w-[110px] cursor-pointer">
                            <span v-text="'@lang('admin::app.components.datagrid.filters.select')'"></span>

                            <span class="icon-arrow-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:menu>
                        <x-shop-pwa::dropdown.menu.item
                            v-for="option in column.options"
                            v-text="option.label"
                            @click="filterPage(option.value, column)"
                        />
                    </x-slot>
                </x-shop-pwa::dropdown>
            </div>

            <div class="mb-4 flex gap-2 flex-wrap">
                <p
                    class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                    v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                >
                    <!-- Retrieving the label from the options based on the applied column value. -->
                    <span v-text="column.options.find((option => option.value == appliedColumnValue)).label"></span>

                    <span
                        class="icon-cross cursor-pointer text-lg text-white ltr:ltr:ml-1.5 rtl:mr-1.5"
                        @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                    >
                    </span>
                </p>
            </div>
        </div>

        <!-- Dropdown -->
        <div v-else-if="column.type === 'dropdown'">
            <!-- Basic -->
            <div v-if="column.options.type === 'basic'">
                <div class="flex items-center justify-between">
                    <p
                        class="text-sm font-medium leading-6 text-gray-800"
                        v-text="column.label"
                    >
                    </p>

                    <div
                        class="flex items-center gap-x-1.5"
                        @click="removeAppliedColumnAllValues(column.index)"
                    >
                        <p
                            class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                            v-if="hasAnyAppliedColumnValues(column.index)"
                        >
                            @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                        </p>
                    </div>
                </div>

                <div class="mb-2 mt-1.5">
                    <x-shop-pwa::dropdown>
                        <!-- Dropdown Toggler -->
                        <x-slot:toggle>
                            <button class="flex justify-between items-center gap-4 w-full ltr:pl-4 rtl:pr-4 ltr:pr-3 rtl:pl-3 py-2 rounded-lg bg-white border border-[#E9E9E9] text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:ltr:pr-2.5 max-md:rtl:pl-2.5 max-md:ltr:pl-2.5 max-md:rtl:pr-2.5 max-md:border-0 max-md:w-[110px] cursor-pointer">
                                <span v-text="'@lang('admin::app.components.datagrid.filters.select')'"></span>

                                <span class="icon-arrow-down text-2xl"></span>
                            </button>
                        </x-slot>

                        <!-- Dropdown Content -->
                        <x-slot:menu>
                            <x-shop-pwa::dropdown.menu.item
                                v-for="option in column.options.params.options"
                                v-text="option.label"
                                @click="filterPage(option.value, column)"
                            />
                        </x-slot>
                    </x-shop-pwa::dropdown>
                </div>

                <div class="mb-4 flex gap-2 flex-wrap">
                    <p
                        class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                        v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                    >
                        <!-- Retrieving the label from the options based on the applied column value. -->
                        <span v-text="column.options.params.options.find((option => option.value == appliedColumnValue)).label"></span>

                        <span
                            class="icon-cancel ltr:ml-1.5 rtl:mr-1.5 cursor-pointer text-lg text-white"
                            @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                        >
                        </span>
                    </p>
                </div>
            </div>

            <!-- Searchable -->
            <div v-else-if="column.options.type === 'searchable'">
                <div class="flex items-center justify-between">
                    <p
                        class="text-sm font-medium leading-6 text-gray-800"
                        v-text="column.label"
                    >
                    </p>

                    <div
                        class="flex items-center gap-x-1.5"
                        @click="removeAppliedColumnAllValues(column.index)"
                    >
                        <p
                            class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                            v-if="hasAnyAppliedColumnValues(column.index)"
                        >
                            @lang('admin::app.components.datagrid.filters.custom-filters.clear-all')
                        </p>
                    </div>
                </div>

                <div class="mb-2 mt-1.5">
                    <v-datagrid-searchable-dropdown
                        :datagrid-id="available.id"
                        :column="column"
                        @select-option="filterPage($event, column)"
                    >
                    </v-datagrid-searchable-dropdown>
                </div>

                <div class="mb-4 flex gap-2 flex-wrap">
                    <p
                        class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                        v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                    >
                        <span v-text="appliedColumnValue"></span>

                        <span
                            class="icon-cancel ltr:ml-1.5 rtl:mr-1.5 cursor-pointer text-lg text-white"
                            @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                        >
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Date Range -->
        <div v-else-if="column.type === 'date_range'">
            <div class="flex items-center justify-between">
                <p
                    class="text-sm font-medium leading-6 text-gray-800"
                    v-text="column.label"
                >
                </p>

                <div
                    class="flex items-center gap-x-1.5"
                    @click="removeAppliedColumnAllValues(column.index)"
                >
                    <p
                        class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                        v-if="hasAnyAppliedColumnValues(column.index)"
                    >
                        @lang('shop::app.components.datagrid.filters.custom-filters.clear-all')
                    </p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-1.5">
                <p
                    class="cursor-pointer rounded-md border border-gray-300 px-2 py-1.5 text-center font-medium leading-6 text-gray-600"
                    v-for="option in column.options"
                    v-text="option.label"
                    @click="filterPage(
                        $event,
                        column,
                        { quickFilter: { isActive: true, selectedFilter: option } }
                    )"
                >
                </p>

                <x-shop-pwa::flat-picker.date ::allow-input="false">
                    <input
                        value=""
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400"
                        :type="column.input_type"
                        :name="`${column.index}[from]`"
                        :placeholder="column.label"
                        :ref="`${column.index}[from]`"
                        @change="filterPage(
                            $event,
                            column,
                            { range: { name: 'from' }, quickFilter: { isActive: false } }
                        )"
                    />
                </x-shop-pwa::flat-picker.date>

                <x-shop-pwa::flat-picker.date ::allow-input="false">
                    <input
                        type="column.input_type"
                        value=""
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400"
                        :name="`${column.index}[to]`"
                        :placeholder="column.label"
                        :ref="`${column.index}[from]`"
                        @change="filterPage(
                            $event,
                            column,
                            { range: { name: 'to' }, quickFilter: { isActive: false } }
                        )"
                    />
                </x-shop-pwa::flat-picker.date>

                <div class="mb-4 flex gap-2 flex-wrap">
                    <p
                        class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                        v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                    >
                        <span v-text="appliedColumnValue.join(' to ')"></span>

                        <span
                            class="icon-cancel ltr:ml-1.5 rtl:mr-1.5 cursor-pointer text-lg text-white"
                            @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                        >
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Date Time Range -->
        <div v-else-if="column.type === 'datetime_range'">
            <div class="flex items-center justify-between">
                <p
                    class="text-sm font-medium leading-6 text-gray-800"
                    v-text="column.label"
                >
                </p>

                <div
                    class="flex items-center gap-x-1.5"
                    @click="removeAppliedColumnAllValues(column.index)"
                >
                    <p
                        class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                        v-if="hasAnyAppliedColumnValues(column.index)"
                    >
                        @lang('shop::app.components.datagrid.filters.custom-filters.clear-all')
                    </p>
                </div>
            </div>

            <div class="my-4 grid grid-cols-2 gap-1.5">
                <p
                    class="cursor-pointer rounded-md border border-gray-300 px-2 py-1.5 text-center font-medium leading-6 text-gray-600"
                    v-for="option in column.options"
                    v-text="option.label"
                    @click="filterPage(
                        $event,
                        column,
                        { quickFilter: { isActive: true, selectedFilter: option } }
                    )"
                >
                </p>

                <x-shop-pwa::flat-picker.datetime ::allow-input="false">
                    <input
                        value=""
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400"
                        :type="column.input_type"
                        :name="`${column.index}[from]`"
                        :placeholder="column.label"
                        :ref="`${column.index}[from]`"
                        @change="filterPage(
                            $event,
                            column,
                            { range: { name: 'from' }, quickFilter: { isActive: false } }
                        )"
                    />
                </x-shop-pwa::flat-picker.datetime>

                <x-shop-pwa::flat-picker.datetime ::allow-input="false">
                    <input
                        type="column.input_type"
                        value=""
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400"
                        :name="`${column.index}[to]`"
                        :placeholder="column.label"
                        :ref="`${column.index}[from]`"
                        @change="filterPage(
                            $event,
                            column,
                            { range: { name: 'to' }, quickFilter: { isActive: false } }
                        )"
                    />
                </x-shop-pwa::flat-picker.datetime>

                <div class="mb-4 flex gap-2 flex-wrap">
                    <p
                        class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                        v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                    >
                        <span v-text="appliedColumnValue.join(' to ')"></span>

                        <span
                            class="icon-cancel ltr:ml-1.5 rtl:mr-1.5 cursor-pointer text-lg text-white"
                            @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                        >
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Rest -->
        <div v-else>
            <div class="flex items-center justify-between">
                <p
                    class="text-sm font-medium leading-6 text-gray-800"
                    v-text="column.label"
                >
                </p>

                <div
                    class="flex items-center gap-x-1.5"
                    @click="removeAppliedColumnAllValues(column.index)"
                >
                    <p
                        class="cursor-pointer text-xs font-medium leading-6 text-blue-600"
                        v-if="hasAnyAppliedColumnValues(column.index)"
                    >
                        @lang('shop::app.components.datagrid.filters.custom-filters.clear-all')
                    </p>
                </div>
            </div>

            <div class="mb-2 mt-1.5 grid">
                <input
                    type="text"
                    class="mb-3 w-full rounded border px-3 py-2 text-sm text-gray-600 shadow transition-all hover:border-gray-400 focus:border-gray-400"
                    :name="column.index"
                    :placeholder="column.label"
                    @keyup.enter="filterPage($event, column)"
                />
            </div>

            <div class="mb-4 flex gap-2 flex-wrap">
                <p
                    class="flex items-center rounded bg-gray-600 px-2 py-1 font-semibold text-white"
                    v-for="appliedColumnValue in getAppliedColumnValues(column.index)"
                >
                    <span v-text="appliedColumnValue"></span>

                    <span
                        class="icon-cancel ltr:ml-1.5 rtl:mr-1.5 cursor-pointer text-lg text-white"
                        @click="removeAppliedColumnValue(column.index, appliedColumnValue)"
                    >
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-datagrid-searchable-dropdown-template">
        <x-shop-pwa::dropdown ::close-on-click="false">
            <!-- Dropdown Toggler -->
            <x-slot:toggle>
                <button class="flex justify-between items-center gap-4 w-full pl-4 pr-3 py-2 rounded-lg bg-white border border-[#E9E9E9] text-sm transition-all hover:border-gray-400 focus:border-gray-400 max-md:pr-2.5 max-md:pl-2.5 max-md:border-0 max-md:w-[110px] cursor-pointer">
                    <span v-text="'@lang('admin::app.components.datagrid.filters.select')'"></span>

                    <span class="icon-arrow-down text-2xl"></span>
                </button>
            </x-slot>

            <!-- Dropdown Content -->
            <x-slot:menu>
                <div class="relative">
                    <div class="relative rounded">
                        <ul class="list-reset">
                            <li class="p-2">
                                <input
                                    class="block w-full rounded-md border border-gray-300 bg-white px-2 py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400"
                                    @keyup="lookUp($event)"
                                >
                            </li>

                            <ul class="p-2">
                                <li v-if="!isMinimumCharacters">
                                    <p
                                        class="hover:bg-grey-light block cursor-pointer p-2 text-black"
                                        v-text="'@lang('shop::app.components.datagrid.filters.dropdown.searchable.atleast-two-chars')'"
                                    >
                                    </p>
                                </li>

                                <li v-else-if="!searchedOptions.length">
                                    <p
                                        class="hover:bg-grey-light block cursor-pointer p-2 text-black"
                                        v-text="'@lang('shop::app.components.datagrid.filters.dropdown.searchable.no-results')'"
                                    >
                                    </p>
                                </li>

                                <li
                                    v-for="option in searchedOptions"
                                    v-else
                                >
                                    <p
                                        class="hover:bg-grey-light block cursor-pointer p-2 text-black"
                                        v-text="option.label"
                                        @click="selectOption(option)"
                                    >
                                    </p>
                                </li>
                            </ul>
                        </ul>
                    </div>
                </div>
            </x-slot>
        </x-shop-pwa::dropdown>
    </script>

    <script type="module">
        app.component('v-datagrid-searchable-dropdown', {
            template: '#v-datagrid-searchable-dropdown-template',

            props: ['datagridId', 'column'],

            data() {
                return {
                    isMinimumCharacters: false,

                    searchedOptions: [],
                };
            },

            methods: {
                lookUp($event) {
                    let params = {
                        datagrid_id: this.datagridId,
                        column: this.column.index,
                        search: $event.target.value,
                    };

                    if (! (params['search'].length > 1)) {
                        this.searchedOptions = [];

                        this.isMinimumCharacters = false;

                        return;
                    }

                    this.$axios
                        .get('{{ route('shop.customer.datagrid.look_up') }}', {
                            params
                        })
                        .then(({
                            data
                        }) => {
                            this.isMinimumCharacters = true;

                            this.searchedOptions = data;
                        });
                },

                selectOption(option) {
                    this.searchedOptions = [];

                    this.$emit('select-option', {
                        target: {
                            value: option.value
                        }
                    });
                },
            },
        });
    </script>
@endpushOnce
