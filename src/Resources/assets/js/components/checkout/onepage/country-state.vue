<template>
    <div>
        <div class="control-group" :class="[errors.has(errorFieldName('country')) ? 'has-error' : '']">
            <select type="text" v-validate="'required'" class="control" :id="type + '[country]'" :name="type + '[country]'" v-model="address.country" :data-vv-as="$t('Country')">
                <option value=""></option>

                <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
            </select>

            <label :for="type + '[country]'" class="required">
                {{ $t('Country') }}
            </label>

            <span class="control-error" v-if="errors.has(errorFieldName('country'))">
                {{ errors.first(errorFieldName('country')) }}
            </span>
        </div>

        <div class="control-group" :class="[errors.has(errorFieldName('state')) ? 'has-error' : '']">
            <input type="text" v-validate="'required'" class="control" :id="type + '[state]'" :name="type + '[state]'" v-model="address.state" v-if="! haveStates(type)" :placeholder="$t('State')" :data-vv-as="$t('State')"/>

            <select v-validate="'required'" class="control" :id="type + '[state]'" :name="type + '[state]'" v-model="address.state" v-if="haveStates(type)" :data-vv-as="$t('State')">

                <option value="">{{ $t('Please Select State') }}</option>

                <option v-for='(state, index) in countryStates[address.country]' :value="state.code">
                    {{ state.default_name }}
                </option>

            </select>

            <label :for="type + '[state]'" class="required">
                {{ $t('State') }}
            </label>

            <span class="control-error" v-if="errors.has(errorFieldName('state'))">
                {{ errors.first(errorFieldName('state')) }}
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'checkout-country-state',

        props: ['address', 'type'],

        data: () => ({
            countries: {},

            countryStates: {}
        }),

        inject: ['$validator'],

        mounted () {
            this.getCountryStateGroup();
            
            this.getCountries();
        },

        methods: {
            getCountries () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/countries', { params: { pagination: 0 } })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.countries = response.data.data;
                    })
                    .catch(function (error) {});
            },

            getCountryStateGroup () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/country-states')
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.countryStates = response.data.data;
                    })
                    .catch(function (error) {});
            },

            haveStates () {
                if (this.countryStates[this.address.country] && this.countryStates[this.address.country].length)
                    return true;

                return false;
            },

            errorFieldName (inputName) {
                return 'address-form.' + this.type + '[' + inputName + ']';
            }
        }
    };
</script>