<template>
    <div class="content">
        <custom-header :title="$t('Orders')"></custom-header>

        <div class="order-list" v-if="orders.length">
            <order-card v-for="order in orders" :key='order.uid' :order="order"></order-card>
        </div>

        <empty-order-list v-else></empty-order-list>

        <pagination :label="$t('Load More Orders')" v-bind="pagination" @onPaginate="paginate($event)"></pagination>
    </div>
</template>

<script>
    import CustomHeader   from '../../../../layouts/custom-header';
    import OrderCard      from './card';
    import Pagination     from '../../../../shared/pagination';
    import EmptyOrderList from './empty-order-list';

    export default {
        name: 'order-list',

        components: { CustomHeader, OrderCard, Pagination, EmptyOrderList },

        data () {
            return {
                orders: [],

                pagination: {},

				params: {
                    'customer_id': this.customer.id
                },
            }
        },

        props: ['customer'],

        mounted () {
            this.getOrders()
        },

        methods: {
            getOrders () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/orders', { params: this.params })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        response.data.data.forEach(function(order) {
                            this_this.orders.push(order);
                        });

                        this_this.pagination = response.data.meta;
                    })
                    .catch(function (error) {});
            },
            
            paginate (page) {
                this.params['page'] = page;

                this.getOrders();
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

        .order-list {
            margin-top: 55px;
        }
    }
</style>