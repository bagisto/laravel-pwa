<template>
    <div>
        <div class="control-group" :class="[errors.has('country') ? 'has-error' : '']">
            <select name="" type="text" v-validate="'required'" class="control" id="country" name="country" v-model="address.country" :data-vv-as="$t('Country')">
                <option value=""></option>
                <option v-for="country in countries" :value="country.code">{{ country.name }}</option>
            </select>

            <label>{{ $t('Country') }}</label>

            <span class="control-error" v-if="errors.has('country')">{{ errors.first('country') }}</span>
        </div>

        <div class="control-group" :class="[errors.has('state') ? 'has-error' : '']">
            <input type="text" v-validate="'required'" class="control" id="state" name="state" v-model="address.state" v-if="! haveStates()" :data-vv-as="$t('State')" :placeholder="$t('State')"/>

            <select v-validate="'required'" class="control" id="state" name="state" v-model="address.state" v-if="haveStates()" :data-vv-as="$t('State')">

                <option value="">{{ $t('Select State') }}</option>

                <option v-for='(state, index) in countryStates[address.country]' :value="state.code">
                    {{ state.default_name }}
                </option>

            </select>

            <label>{{ $t('State') }}</label>

            <span class="control-error" v-if="errors.has('state')">{{ errors.first('state') }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'country-state',

        props: ['address'],

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
            }
        }
    };
</script>