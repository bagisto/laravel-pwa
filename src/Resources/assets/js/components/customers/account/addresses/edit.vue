<template>
    <div class="content" v-if="address">
        <custom-header :title="$t('Edit Address')"></custom-header>
        
        <form action="POST" @submit.prevent="validateBeforeSubmit">
            <div class="form-container">
                <div class="control-group" :class="[errors.has('address1[]') ? 'has-error' : '']">
                    <input type="text" name="address1[]" class="control" v-model="address.address1[0]" v-validate="'required'" :placeholder="$t('Street Address 1')" :data-vv-as="$t('Street Address 1')"/>
                    <label>{{ $t('Street Address 1') }}</label>
                    <span class="control-error" v-if="errors.has('address1[]')">{{ errors.first('address1[]') }}</span>
                </div>

                <div class="control-group" v-if="streetLines && streetLines > 0" v-for="n in parseInt(streetLines)" style="margin-top: -15px;">
                    <input type="text" name="address1[]" class="control" v-model="address.address1[n]" :placeholder="$t('Street Address number', {number: n + 1})">
                    <label>{{ $t('Street Address number', {number: n + 1}) }}</label>
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
        name: 'edit-address',

        props: ['customer'],

        data () {
            return {
                loading: false,

                streetLines: 0,

                address: null
            }
        },

        components: { CustomHeader, CountryState },

        mounted () {
            this.getAddress(this.$route.params.id)

            this.getConfigData()
        },

        methods: {
            getAddress (addressId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/addresses/' + addressId)
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.address = response.data.data;
                    })
                    .catch(function (error) {});
            },

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

            validateBeforeSubmit () {
                this.loading = true;

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.updateAddress()
                    } else {
                        this.loading = false;
                    }
                });
            },

            updateAddress () {
                var this_this = this;
                
                EventBus.$emit('show-ajax-loader');

                this.$http.put('/api/addresses/' + this.address.id, this.address)
                    .then(function(response) {
                        this_this.loading = false;

                        this_this.$toasted.show(response.data.message, {type: 'success'})

                        EventBus.$emit('hide-ajax-loader');

                        this_this.$router.push({name: 'dashboard'})
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
            margin-top: 55px;
        }
    }
</style>