<template>
    <div class="content">
        <custom-header :title="$t('Address Book')"></custom-header>

        <div class="address-list" v-if="addresses.length">
            <address-card v-for="address in addresses" :key='address.uid' :address="address" @onRemove="removeAddress(address)"></address-card>

            <router-link class="btn btn-black" :to="'/customer/account/addresses/create'">{{ $t('Add New Address') }}</router-link>
        </div>

        <empty-address-list v-else></empty-address-list>
    </div>
</template>

<script>
    import CustomHeader     from '../../../layouts/custom-header';
    import AddressCard      from './card';
    import EmptyAddressList from './empty-address-list';

    export default {
        name: 'address-list',

        components: { CustomHeader, AddressCard, EmptyAddressList },

        props: ['customer'],

        data () {
            return {
                addresses: []
            }
        },

        mounted () {
            this.getAddresses()
        },

        methods: {
            getAddresses () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/v1/customer/addresses')
                    .then(function(response) {

                        EventBus.$emit('hide-ajax-loader');

                        this_this.addresses = response.data.data;
                    })
                    .catch(function (error) {});
            },

            removeAddress (address) {
                let index = this.addresses.indexOf(address)

                this.addresses.splice(index, 1);
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

        .btn.btn-black {
            width: 100%;

            .icon {
                margin-right: 8px;
                vertical-align: middle;
            }

            span {
                vertical-align: middle;
            }
        }
    }
</style>
