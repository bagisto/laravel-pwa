<template>
    <div class="content dashboard">
        <custom-header>
            <div slot="content">
                <div class="account-information">
                    <h2>{{ $t('Dashboard') }}</h2>

                    <div class="avatar"></div>

                    <div class="name">{{ customer.first_name + ' ' + customer.last_name }}</div>

                    <div class="email">{{ customer.email }}</div>

                    <router-link class="edit-info-btn" :to="'/customer/account/profile'">{{ $t('Edit Info') }}</router-link>
                </div>
            </div>
        </custom-header>

        <div class="account-content">
            <tabs>
                <tab :name="$t('Recent Orders')" :selected="true">
                    <div v-if="orders.length">
                        <div :class="['order-list', haveMoreOrders ? 'have-more-orders' : '']">
                            <order-card v-for="order in orders" :key='order.uid' :order="order"></order-card>
                        </div>

                        <div class="load-more" v-if="haveMoreOrders">
                            <router-link class="btn btn-black" :to="'/customer/account/orders'">{{ $t('View All Orders') }}</router-link>
                        </div>
                    </div>

                    <empty-order-list v-else></empty-order-list>
                </tab>

                <tab :name="$t('Address')">
                    <div class="address-list" v-if="addresses.length">
                        <address-card v-for="address in addresses" :key='address.uid' :address="address" @onRemove="removeAddress(address)"></address-card>
                    </div>

                    <empty-address-list v-else></empty-address-list>
                </tab>

                <tab :name="$t('Reviews')">
                    <div v-if="reviews.length">
                        <div class="review-list">
                            <review-card v-for="review in reviews" :key='review.uid' :review="review"></review-card>
                        </div>

                        <div class="load-more" v-if="haveMoreReviews">
                            <router-link class="btn btn-black" :to="'/customer/account/reviews'">{{ $t('View All Reviews') }}</router-link>
                        </div>
                    </div>

                    <empty-review-list v-else></empty-review-list>
                </tab>
            </tabs>
        </div>
    </div>
</template>

<script>
    import CustomHeader     from '../../../layouts/custom-header';
    import Tabs             from '../../../shared/tabs';
    import Tab              from '../../../shared/tab';
    import OrderCard        from '../sales/orders/card';
    import AddressCard      from '../addresses/card';
    import ReviewCard       from '../reviews/card';
    import EmptyOrderList   from '../sales/orders/empty-order-list';
    import EmptyAddressList from '../addresses/empty-address-list';
    import EmptyReviewList  from '../reviews/empty-review-list';

    export default {
        name: 'dashboard',

        components: { CustomHeader, Tabs, Tab, OrderCard, AddressCard, ReviewCard, EmptyOrderList, EmptyAddressList, EmptyReviewList },

        data () {
            return {
                orders: [],
                
                haveMoreOrders: false,

                addresses: [],

                reviews: [],

                haveMoreReviews: false,
            }
        },

        props: ['customer'],

        mounted () {
            this.getOrders()

            this.getAddresses()

            this.getReviews()
        },

        methods: {
            getOrders () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/orders', { params: { customer_id: this.customer.id } })
                    .then(function(response) {
                        this_this.orders = response.data.data;

                        if (response.data.meta.current_page < response.data.meta.last_page) {
                            this_this.haveMoreOrders = true;
                        }

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            },

            getAddresses () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/addresses', { params: { customer_id: this.customer.id, pagination: 0 } })
                    .then(function(response) {
                        this_this.addresses = response.data.data;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            },

            removeAddress (address) {
                let index = this.addresses.indexOf(address)

                this.addresses.splice(index, 1);
            },

            getReviews () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/reviews', { params: { customer_id: this.customer.id, status: 'approved' } })
                    .then(function(response) {
                        this_this.reviews = response.data.data;

                        if (response.data.meta.current_page < response.data.meta.last_page) {
                            this_this.haveMoreReviews = true;
                        }

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
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

        .account-information {
            min-height: 270px;
            padding: 16px;
            color: #ffffff;
            text-align: center;

            h2 {
                margin-top: 3px;
            }

            .avatar {
                width: 72px;
                height: 72px;
                border-radius: 50%;
                margin-top: 30px;
                margin-bottom: 4px;
            }

            .name {
                font-weight: 600;
                font-size: 14px;
            }

            .edit-info-btn {
                background: rgba(255, 255, 255, 0.16);
                color: #FFFFFF;
                font-weight: 600;
                text-transform: uppercase;
                border-radius: 16px;
                padding: 9px 16px;
                margin-top: 20px;
                display: inline-block;
            }
        }

        .order-list {
            &.have-more-orders {
                padding-bottom: 88px;
            }
        }

        .load-more {
            position: fixed;
            bottom: 0;
            padding: 6px 8px;
            width: 100%;
            background: #ffffff;
            box-shadow: 0 -10px 10px 0 rgba(0, 0, 0, 0.04), 0 -1px 4px 0 rgba(0, 0, 0, 0.16);

            .btn-black {
                width: 100%;
            }
        }
    }
</style>