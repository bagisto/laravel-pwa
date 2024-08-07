<x-shop-pwa::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.edit')
        @lang('shop::app.customers.account.addresses.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop-pwa::breadcrumbs
            name="addresses.edit"
            :entity="$address"
        />
    @endSection

    <h2 class="mb-8 text-2xl font-medium">
        @lang('shop::app.customers.account.addresses.edit')
        @lang('shop::app.customers.account.addresses.title')
    </h2>

    {!! view_render_event('bagisto.shop.customers.account.address.edit.before', ['address' => $address]) !!}

    <!-- Customer Address edit Component-->
    <v-edit-customer-address>
        <!-- Address Shimmer -->
        <x-shop-pwa::shimmer.form.control-group :count="10" />
    </v-edit-customer-address>

    @push('scripts')
        <script type="text/x-template" id="v-edit-customer-address-template">

            <!-- Edit Address Form -->
            <x-shop-pwa::form
                method="PUT"
                :action="route('shop.customers.account.addresses.update',  $address->id)"
            >
                {!! view_render_event('bagisto.shop.customers.account.address.edit_form_controls.before', ['address' => $address]) !!}

                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label>
                        @lang('shop::app.customers.account.addresses.company-name')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="text"
                        name="company_name"
                        :value="old('company_name') ?? $address->company_name"
                        :label="trans('shop::app.customers.account.addresses.company-name')"
                        :placeholder="trans('shop::app.customers.account.addresses.company-name')"
                    />

                    <x-shop-pwa::form.control-group.error control-name="company_name" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.company_name.after', ['address' => $address]) !!}

                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label class="required">
                        @lang('shop::app.customers.account.addresses.first-name')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="text"
                        name="first_name"
                        rules="required"
                        :value="old('first_name') ?? $address->first_name"
                        :label="trans('shop::app.customers.account.addresses.first-name')"
                        :placeholder="trans('shop::app.customers.account.addresses.first-name')"
                    />

                    <x-shop-pwa::form.control-group.error control-name="first_name" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.first_name.after', ['address' => $address]) !!}

                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label class="required">
                        @lang('shop::app.customers.account.addresses.last-name')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="text"
                        name="last_name"
                        rules="required"
                        :value="old('last_name') ?? $address->last_name"
                        :label="trans('shop::app.customers.account.addresses.last-name')"
                        :placeholder="trans('shop::app.customers.account.addresses.last-name')"
                    />

                    <x-shop-pwa::form.control-group.error control-name="last_name" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.last_name.after', ['address' => $address]) !!}

                <!-- E-mail -->
                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label class="required">
                        @lang('Email')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="text"
                        name="email"
                        rules="required|email"
                        :value="old('email') ?? $address->email"
                        :label="trans('Email')"
                        :placeholder="trans('Email')"
                    />

                    <x-shop-pwa::form.control-group.error control-name="email" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.email.after', ['address' => $address]) !!}

                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label>
                        @lang('shop::app.customers.account.addresses.vat-id')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="text"
                        name="vat_id"
                        :value="old('vat_id') ?? $address->vat_id"
                        :label="trans('shop::app.customers.account.addresses.vat-id')"
                        :placeholder="trans('shop::app.customers.account.addresses.vat-id')"
                    />

                    <x-shop-pwa::form.control-group.error control-name="vat_id" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.vat_id.after', ['address' => $address]) !!}

                @php
                    $addresses = explode(PHP_EOL, $address->address);
                @endphp

                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label class="required">
                        @lang('shop::app.customers.account.addresses.street-address')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="text"
                        name="address[]"
                        :value="collect(old('address'))->first() ?? $addresses[0]"
                        rules="required|address"
                        :label="trans('shop::app.customers.account.addresses.street-address')"
                        :placeholder="trans('shop::app.customers.account.addresses.street-address')"
                    />

                    <x-shop-pwa::form.control-group.error control-name="address[]" />
                </x-shop-pwa::form.control-group>

                @if (
                    core()->getConfigData('customer.address.information.street_lines')
                    && core()->getConfigData('customer.address.information.street_lines') > 1
                )
                    @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                        <x-shop-pwa::form.control-group.control
                            type="text"
                            name="address[{{ $i }}]"
                            :value="old('address[{{$i}}]', $addresses[$i] ?? '')"
                            rules="address"
                            :label="trans('shop::app.customers.account.addresses.street-address')"
                            :placeholder="trans('shop::app.customers.account.addresses.street-address')"
                        />

                        <x-shop-pwa::form.control-group.error
                            class="mb-2"
                            name="address[{{ $i }}]"
                        />
                    @endfor
                @endif

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.street-addres.after', ['address' => $address]) !!}

                <!-- Country Name -->
                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                        @lang('shop::app.customers.account.addresses.country')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="select"
                        name="country"
                        rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                        v-model="addressData.country"
                        :aria-label="trans('shop::app.customers.account.addresses.country')"
                        :label="trans('shop::app.customers.account.addresses.country')"
                    >
                        @foreach (core()->countries() as $country)
                            <option
                                {{ $country->code === config('app.default_country') ? 'selected' : '' }}
                                value="{{ $country->code }}"
                            >
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </x-shop-pwa::form.control-group.control>

                    <x-shop-pwa::form.control-group.error control-name="country" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.country.after', ['address' => $address]) !!}

                <!-- State Name -->
                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }}">
                        @lang('shop::app.customers.account.addresses.state')
                    </x-shop-pwa::form.control-group.label>
                    <template v-if="haveStates()">
                        <x-shop-pwa::form.control-group.control
                            type="select"
                            name="state"
                            id="state"
                            rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                            v-model="addressData.state"
                            :label="trans('shop::app.customers.account.addresses.state')"
                            :placeholder="trans('shop::app.customers.account.addresses.state')"
                        >
                            <option
                                v-for='(state, index) in countryStates[addressData.country]'
                                :value="state.code"
                                v-text="state.default_name"
                            >
                            </option>
                        </x-shop-pwa::form.control-group.control>
                    </template>

                    <template v-else>
                        <x-shop-pwa::form.control-group.control
                            type="text"
                            name="state"
                            rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                            :value="old('state') ?? $address->state"
                            :label="trans('shop::app.customers.account.addresses.state')"
                            :placeholder="trans('shop::app.customers.account.addresses.state')"
                        />
                    </template>

                    <x-shop-pwa::form.control-group.error control-name="state" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.state.after', ['address' => $address]) !!}

                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label class="required">
                        @lang('shop::app.customers.account.addresses.city')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="text"
                        name="city"
                        rules="required"
                        :value="old('city') ?? $address->city"
                        :label="trans('shop::app.customers.account.addresses.city')"
                        :placeholder="trans('shop::app.customers.account.addresses.city')"
                    />

                    <x-shop-pwa::form.control-group.error control-name="city" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.city.after', ['address' => $address]) !!}

                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }}">
                        @lang('shop::app.customers.account.addresses.post-code')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="text"
                        name="postcode"
                        rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}|numeric "
                        :value="old('postal-code') ?? $address->postcode"
                        :label="trans('shop::app.customers.account.addresses.post-code')"
                        :placeholder="trans('shop::app.customers.account.addresses.post-code')"
                    />

                    <x-shop-pwa::form.control-group.error control-name="postcode" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.postcode.after', ['address' => $address]) !!}

                <x-shop-pwa::form.control-group>
                    <x-shop-pwa::form.control-group.label class="required">
                        @lang('shop::app.customers.account.addresses.phone')
                    </x-shop-pwa::form.control-group.label>

                    <x-shop-pwa::form.control-group.control
                        type="text"
                        name="phone"
                        rules="required|integer"
                        :value="old('phone') ?? $address->phone"
                        :label="trans('shop::app.customers.account.addresses.phone')"
                        :placeholder="trans('shop::app.customers.account.addresses.phone')"
                    />

                    <x-shop-pwa::form.control-group.error control-name="phone" />
                </x-shop-pwa::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.addresses.edit_form_controls.phone.after', ['address' => $address]) !!}

                <button
                    type="submit"
                    class="primary-button m-0 block text-base w-max py-3 px-11 rounded-2xl text-center"
                >
                    @lang('shop::app.customers.account.addresses.save')
                </button>

                {!! view_render_event('bagisto.shop.customers.account.address.edit_form_controls.after', ['address' => $address]) !!}

            </x-shop-pwa::form>
        </script>

        <script type="module">
            app.component('v-edit-customer-address', {
                template: '#v-edit-customer-address-template',

                data() {
                    return {
                        addressData: {
                            country: "{{ old('country') ?? $address->country }}",

                            state: "{{ old('state') ?? $address->state }}",
                        },

                        countryStates: @json(core()->groupedStatesByCountries()),
                    };
                },

                methods: {
                    haveStates() {
                        return !!this.countryStates[this.addressData.country]?.length;
                    },
                },
            });
        </script>
    @endpush
    {!! view_render_event('bagisto.shop.customers.account.address.edit.after', ['address' => $address]) !!}

</x-shop-pwa::layouts.account>
