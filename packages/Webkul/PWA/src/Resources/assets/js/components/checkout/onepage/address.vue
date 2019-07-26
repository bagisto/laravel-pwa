<template>
    <div>
        <div class="control-group" :class="[errors.has('address-form.' + type + '[first_name]') ? 'has-error' : '']">
            <input type="text" :name="type + '[first_name]'" class="control" v-model="address.first_name" v-validate="'required'" :placeholder="$t('First Name')" :data-vv-as="$t('First Name')" onkeypress="return (event.charCode > 64 &&
            event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"/>
            <label>{{ $t('First Name') }}</label>
            <span class="control-error" v-if="errors.has('address-form.' + type + '[first_name]')">{{ errors.first('address-form.' + type + '[first_name]') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.' + type + '[last_name]') ? 'has-error' : '']">
            <input type="text" :name="type + '[last_name]'" class="control" v-model="address.last_name" v-validate="'required'" :placeholder="$t('Last Name')" :data-vv-as="$t('Last Name')" onkeypress="return (event.charCode > 64 &&
            event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"/>
            <label>{{ $t('Last Name') }}</label>
            <span class="control-error" v-if="errors.has('address-form.' + type + '[last_name]')">{{ errors.first('address-form.' + type + '[last_name]') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.' + type + '[email]') ? 'has-error' : '']">
            <input type="email" :name="type + '[email]'" class="control" v-model="address.email" v-validate="'required'" :placeholder="$t('Email')" :data-vv-as="$t('Email')"/>
            <label>{{ $t('Email') }}</label>
            <span class="control-error" v-if="errors.has('address-form.' + type + '[email]')">{{ errors.first('address-form.' + type + '[email]') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.' + type + '[address1][]') ? 'has-error' : '']">
            <input type="text" :name="type + '[address1][]'" class="control" v-model="address.address1[0]" v-validate="'required'" :placeholder="$t('Street Address 1')" :data-vv-as="$t('Street Address 1')"/>
            <label>{{ $t('Street Address 1') }}</label>
            <span class="control-error" v-if="errors.has('address-form.' + type + '[address1][]')">{{ errors.first('address-form.' + type + '[address1][]') }}</span>
        </div>

        <div class="control-group" v-if="streetLines && streetLines > 0" v-for="n in parseInt(streetLines)" style="margin-top: -15px;">
            <input type="text" name="address1[]" class="control" v-model="address.address1[n]" :placeholder="$t('Street Address number', {number:  + n + 1})">
            <label>{{ $t('Street Address number', {number:  + n + 1}) }}</label>
        </div>

        <checkout-country-state :address="address" :type="type"></checkout-country-state>

        <div class="control-group" :class="[errors.has('address-form.' + type + '[city]') ? 'has-error' : '']">
            <input type="text" :name="type + '[city]'" class="control" v-model="address.city" v-validate="'required'" :placeholder="$t('City')" :data-vv-as="$t('City')"/>
            <label>{{ $t('City') }}</label>
            <span class="control-error" v-if="errors.has('address-form.' + type + '[city]')">{{ errors.first('address-form.' + type + '[city]') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.' + type + '[postcode]') ? 'has-error' : '']">
            <input type="number" :name="type + '[postcode]'" class="control" v-model="address.postcode" v-validate="'required'" :placeholder="$t('Postal Code')" :data-vv-as="$t('City')"/>
            <label>{{ $t('Postal Code') }}</label>
            <span class="control-error" v-if="errors.has('address-form.' + type + '[postcode]')">Postal Code is Required.</span>
        </div>

        <div class="control-group" :class="[errors.has('address-form.' + type + '[phone]') ? 'has-error' : '']">
            <input type="number" :name="type + '[phone]'" class="control" v-model="address.phone" v-validate="'required'" :placeholder="$t('Phone')" :data-vv-as="$t('City')"/>
            <label>{{ $t('Phone') }}</label>
            <span class="control-error" v-if="errors.has('address-form.' + type + '[phone]')">Phone Number is required.</span>
        </div>
    </div>
</template>

<script>
    import CheckoutCountryState from './country-state';

    export default {
        name: 'checkout-address',

        components: { CheckoutCountryState },

        props: [ 'address', 'type' ],

        data () {
            return {
                streetLines: 2,
            }
        },

        inject: [ '$validator' ],

        mounted () {
            this.getConfigData()
        },

        methods: {
            getConfigData () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/config', { params: { '_config': 'customer.settings.address.street_lines' } })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        if (response.data.data['customer.settings.address.street_lines']) {
                            this_this.streetLines = response.data.data['customer.settings.address.street_lines'];

                            this_this.streetLines = this_this.streetLines - 1;
                        }
                    })
                    .catch(function (error) {});
            },

            firstNameValidation() {

            }
        }
    };
</script>