<template>
    <div class="content">
        <custom-header :title="$t('Create Address')"></custom-header>

        <form action="POST" @submit.prevent="validateBeforeSubmit">
            <div class="form-container">

            <div class="control-group" :class="[errors.has('company_name') ? 'has-error' : '']">
                    <input type="text" name="company_name" class="control" v-model="address.company_name" v-validate="'required'" :placeholder="$t('Company Name')" :data-vv-as="$t('Company Name')"/>
                    <label>{{ $t('Company Name') }}</label>
                    <span class="control-error" v-if="errors.has('company_name')">{{ errors.first('company_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <input type="text" name="first_name" class="control" v-model="address.first_name" v-validate="'required'" :placeholder="$t('First Name')" :data-vv-as="$t('First Name')"/>
                    <label>{{ $t('First Name') }}</label>
                    <span class="control-error" v-if="errors.has('first_name')">{{ errors.first('first_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                    <input type="text" name="last_name" class="control" v-model="address.last_name" v-validate="'required'" :placeholder="$t('Last Name')" :data-vv-as="$t('Last Name')"/>
                    <label>{{ $t('Last Name') }}</label>
                    <span class="control-error" v-if="errors.has('last_name')">{{ errors.first('last_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <input type="text" name="email" class="control" v-model="address.email" v-validate="'required'" :placeholder="$t('Email')" :data-vv-as="$t('Email')"/>
                    <label>{{ $t('Email') }}</label>
                    <span class="control-error" v-if="errors.has('email')">{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('vat_id') ? 'has-error' : '']">
                    <input type="text" name="vat_id" class="control" v-model="address.vat_id" v-validate="'required'" :placeholder="$t('vat id')" :data-vv-as="$t('vat id')"/>
                    <label>{{ $t('vat id') }}</label>
                    <span class="control-error" v-if="errors.has('vat_id')">{{ errors.first('vat_id') }}</span>
                </div>

                <div class="control-group" v-if="streetLines && streetLines > 0" v-for="n in parseInt(streetLines)" style="margin-top: -15px;">
                    <input type="text" name="address1[]" class="control" v-model="address.address[n-1]" :placeholder="$t('Street Address number', {number: n })">
                    <label>{{ $t('Street Address number', {number: n }) }}</label>
                </div>

                <country-state :address="address"></country-state>

                <div class="control-group" :class="[errors.has('city') ? 'has-error' : '']">
                    <input type="text" name="city" class="control" v-model="address.city" v-validate="'required'" :placeholder="$t('City')" :data-vv-as="$t('City')"/>
                    <label>{{ $t('City') }}</label>
                    <span class="control-error" v-if="errors.has('city')">{{ errors.first('city') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('postcode') ? 'has-error' : '']">
                    <input type="text" name="postcode" class="control" v-model="address.postcode" v-validate="'required'" :placeholder="$t('Postal Code')" :data-vv-as="$t('Postal Code')"/>
                    <label>{{ $t('Postal Code') }}</label>
                    <span class="control-error" v-if="errors.has('postcode')">{{ errors.first('postcode') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                    <input type="text" name="phone" class="control" v-model="address.phone" v-validate="'required'" :placeholder="$t('Phone')" :data-vv-as="$t('Phone')"/>
                    <label>{{ $t('Phone') }}</label>
                    <span class="control-error" v-if="errors.has('phone')">{{ errors.first('phone') }}</span>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-black btn-lg" :disabled="loading">{{ $t('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import CustomHeader from '../../../layouts/custom-header';
    import CountryState from './country-state';

    export default {
        name: 'create-address',

        props: ['customer'],

        data () {
            return {
                loading: false,

                streetLines: 0,

                address: {
                    company_name: null,
                    first_name: null,
                    last_name: null,
                    address: [],
                    country: null,
                    state:null,
                    city: null,
                    postcode:null,
                    phone: null,
                    email: null,
                    vat_id:null,
                }
            }
        },

        components: { CustomHeader, CountryState },

        mounted () {
            this.getConfigData()
        },

        methods: {
            getConfigData () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                let street_lines = 'customer.address.information.street_lines';

                const configKeys = [
                    street_lines,
                ];

                this.$http.get('/api/v1/core-configs', {
                        params: {
                            _config: configKeys
                        }
                    })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        if (response.data.data[street_lines]) {
                            this_this.streetLines = response.data.data[street_lines];

                            this_this.streetLines = this_this.streetLines - 1;
                        }
                    })
                    .catch(function (error) {});
            },

            validateBeforeSubmit () {
                this.loading = true;

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.createAddress()
                    } else {
                        this.loading = false;
                    }
                });
            },

            createAddress () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.post('/api/v1/customer/addresses', this.address)
                    .then(function(response) {
                        this_this.loading = false;

                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        this_this.$router.push({ name: 'address-list' })
                    })
                    .catch(function (error) {
                        var errors = error.response.data.errors;

                        for (name in errors) {
                            if (errors.hasOwnProperty(name)) {
                                this_this.errors.add(name, errors[name][0])
                            }
                        }

                        this_this.loading = false;
                    })
            }
        }
    }
</script>

<style scoped lang="scss">
    .content {
        position: absolute;
        bottom: 0;
        top: 0;
        width: 100%;
        background: #ffffff;

        .form-container {
            padding: 24px 16px;
        }
    }
</style>
