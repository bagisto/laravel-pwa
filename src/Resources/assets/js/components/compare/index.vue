<template>
    <div class="content">
        <custom-header :title="$t('Compare')"></custom-header>

        <div class="panel" v-if="compare.length">
            <div class="panel-content">
                <div class="compare-list product-list product-grid-2">

                    <compare-card 
                        v-for="item in compare"
                        :key='item.uid'
                        :compareItem="item"
                        @onRemove="removecompareItem(item)"
                        @onMoveToCart="moveToCart(item)"
                    ></compare-card>

                </div>
            </div>
        </div>

        <empty-compare v-else></empty-compare>
    </div>
</template>

<script>
    import CustomHeader  from '../layouts/custom-header';
    import compareCard  from './card';
    import Emptycompare from './empty-compare';
    import {
        mapState,
        mapActions
    } from 'vuex';

    export default {
        name: 'compare',

        components: { CustomHeader, compareCard, Emptycompare },

        data () {
            return {
                compare: []
            }
        },

        computed: mapState({
            customer: state => state.customer,
        }),

        mounted () {
            this.getcompare()
        },

        methods: {
            ...mapActions([
                'getCustomer',
            ]),

            getcompare () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                let items = '';
                let url = '';
                let data = {
                    params: {'data': true}
                }

                if (! this_this.customer) {
                    url = '/api/pwa/detailed-products';
                    items = JSON.parse(localStorage.getItem('compared_product'));
                    items = items ? items.join('&') : '';

                    data = {
                        params: {
                            items
                        }
                    };
                } else {
                    url = '/api/pwa/comparison/get-products';
                }

                if (this_this.customer || (! this_this.customer && items != "")) {
                    this_this.$http.get(url, data)
                    .then(response => {
                        EventBus.$emit('hide-ajax-loader');
                        this_this.compare = response.data.products;
                    })
                    .catch(error => {
                        EventBus.$emit('hide-ajax-loader');
                        console.log(this.__('error.something_went_wrong'));
                    });
                } else {
                    EventBus.$emit('hide-ajax-loader');
                }
            },

            removecompareItem (item) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');
                
                this.$http.delete('/api/compare/' + item.id)
                    .then(function(response) {
                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        var index = this_this.compare.indexOf(item);

                        this_this.compare.splice(index, 1);
                    })
                    .catch(function (error) {});
            },

            moveToCart (item) {
                EventBus.$emit('show-ajax-loader');
                
                this.$http.get('/api/pwa/move-to-cart/' + item.id)
                    .then(response => {
                        this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        var index = this.compare.indexOf(item);

                        this.compare.splice(index, 1);
                        
                        EventBus.$emit('checkout.cart.changed', response.data.data);
                    })
                    .catch(error => {
                        if (error.response.data.data == -1) {
                            this.$router.push({ path: '/products/' + item.product.id })
                        } else if (error.response.data.message) {
                            this.$router.push({ path: '/products/' + item.product.id })

                            this.$toasted.show(error.response.data.message, { type: 'error' })
                        }
                    });
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
    }
</style>