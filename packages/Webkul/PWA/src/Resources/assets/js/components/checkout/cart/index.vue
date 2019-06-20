<template>
    <div class="content">
        <custom-header :title="$t('Cart')"></custom-header>

        <div class="cart-container" v-if="cart && cart.items.length">
            <h2 class="item-count">
                {{ $t('number Item(s)', { number: cart.items.length }) }}
            </h2>

            <div class="cart-item-list">
                <cart-item
                    v-for="cartItem in cart.items"
                    :key='cartItem.uid'
                    :cart-item="cartItem"
                    :quantities="quantities"
                    @moveToWishlist="moveToWishlist(cartItem)"
                    @removeItem="removeItem(cartItem)"
                ></cart-item>
            </div>

            <div class="panel cart-actions">
                <div class="panel-heading">
                    <div class="update-cart-link" @click="updateCart">
                        <i class="icon update-icon"></i>

                        <span>{{ $t('Update Cart') }}</span>
                    </div>

                    <div class="empty-cart-link" @click="emptyCart">
                        <i class="icon empty-cart-icon"></i>

                        <span>{{ $t('Empty Cart') }}</span>
                    </div>
                </div>

                <div class="panel-content">
                    <router-link :to="'/'">{{ $t('Continue Shopping') }}</router-link>
                </div>
            </div>

            <div class="total-summary">
                <accordian :title="$t('Order Summary')" :active="true">
                    <div slot="body">

                        <table>
                            <tbody>
                                <tr>
                                    <td>{{ $t('Subtotal') }}</td>
                                    <td>{{ cart.formated_sub_total }}</td>
                                </tr>

                                <tr>
                                    <td>{{ $t('Tax') }}</td>
                                    <td>{{ cart.formated_tax_total }}</td>
                                </tr>

                                <tr class="last">
                                    <td>{{ $t('Order Total') }}</td>
                                    <td>{{ cart.formated_grand_total }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </accordian>
            </div>

            <div class="checkout-action">
                <span class="total-info">
                    <p>{{ $t('Amount to be paid') }}</p>
                    <h3>{{ cart.formated_grand_total }}</h3>
                </span>

                <router-link class="btn btn-black" :to="'onepage'">{{ $t('Proceed') }}</router-link>
            </div>
        </div>

        <empty-cart v-else></empty-cart>
    </div>
</template>

<script>
    import CustomHeader from '../../layouts/custom-header';
    import EmptyCart    from './empty-cart';
    import CartItem     from './item';
    import Accordian    from '../../shared/accordian';

    export default {
        name: 'cart',

        components: { CustomHeader, CartItem, Accordian, EmptyCart },

        data () {
            return {
                cart: null,

                quantities: {}
            }
        },

        mounted () {
            this.getCart();
        },

        methods: {
            getCart () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/checkout/cart')
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.cart = response.data.data;

                        EventBus.$emit('checkout.cart.changed', this_this.cart);
                    })
                    .catch(function (error) {});
            },

            moveToWishlist (item) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/checkout/cart/move-to-wishlist/' + item.id)
                    .then(function(response) {
                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        this_this.cart = response.data.data;

                        EventBus.$emit('checkout.cart.changed', this_this.cart);
                    })
                    .catch(function (error) {});
            },

            removeItem (item) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/checkout/cart/remove-item/' + item.id)
                    .then(function(response) {
                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        this_this.cart = response.data.data;

                        EventBus.$emit('checkout.cart.changed', this_this.cart);
                    })
                    .catch(function (error) {});
            },

            emptyCart () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/checkout/cart/empty')
                    .then(function(response) {
                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        this_this.cart = null;

                        EventBus.$emit('checkout.cart.changed', this_this.cart);
                    })
                    .catch(function (error) {});
            },

            updateCart () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.put('/api/checkout/cart/update', { 'qty': this.quantities })
                    .then(function(response) {
                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        this_this.cart = response.data.data;

                        EventBus.$emit('checkout.cart.changed', this_this.cart);
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

        .cart-container {
            margin-top: 80px;
            padding-bottom: 60px;

            .item-count {
                font-weight: 700;
                font-size: 12px;
                color: rgba(0, 0, 0, 0.86);
                padding: 16px;
                background: #ffffff;
                border-bottom: 1px solid rgba(0, 0, 0 ,0.12);
                text-transform: uppercase;
            }

            .total-summary {
                margin-bottom: 16px;

                table {
                    width: 100%;

                    tr {
                        td {
                            padding: 8px 0;

                            &:first-child {
                                font-size: 14px;
                                color: rgba(0, 0, 0, 0.56);
                            }

                            &:last-child {
                                font-size: 14px;
                                text-align: right;
                            }
                        }

                        &.last {
                            td {
                                padding: 16px 0 0 0;
                                border-top: 1px solid rgba(0, 0, 0, 0.12);

                                &:first-child {
                                    font-size: 18px;
                                    color: rgba(0, 0, 0, 0.56);
                                }

                                &:last-child {
                                    font-size: 18px;
                                    font-weight: 600;
                                    color: rgba(0, 0, 0, 0.87);
                                }
                            }
                        }
                    }
                }
            }

            .panel.cart-actions {
                font-weight: 600;
                font-size: 14px;
                background: #fff;
                margin-top: 28px;

                .panel-heading {
                    padding: 0;
                    border-bottom: 1px solid rgba(0, 0, 0 ,0.12);

                    .update-cart-link {
                        float: left;
                        width: 50%;
                        display: inline-block;
                        padding: 16px;
                        cursor: pointer;
                        color: rgba(0, 0, 0, 0.87);

                        .icon {
                            float: left;
                            margin-right: 8px;
                            opacity: 0.56;
                        }

                        span {
                            line-height: 24px;
                        }
                    }

                    .empty-cart-link {
                        width: 50%;
                        display: inline-block;
                        padding: 16px;
                        cursor: pointer;
                        color: rgba(0, 0, 0, 0.87);

                        .icon {
                            float: left;
                            margin-right: 8px;
                            opacity: 0.56;
                        }

                        span {
                            line-height: 24px;
                        }
                    }
                }

                .panel-content {
                    border-bottom: 1px solid rgba(0, 0, 0 ,0.12);

                    a {
                        border: 3px solid #000000;
                        font-size: 14px;
                        font-weight: 600;
                        color: #000000;
                        width: 100%;
                        background: #ffffff;
                        padding: 15px;
                        display: block;
                        text-align: center;
                    }
                }
            }


            .checkout-action {
                position: fixed;
                bottom: 0;
                padding: 6px 8px;
                width: 100%;
                background: #ffffff;
                -webkit-box-shadow: 0 -10px 10px 0 rgba(0, 0, 0, 0.04), 0 -1px 4px 0 rgba(0, 0, 0, 0.16);
                box-shadow: 0 -10px 10px 0 rgba(0, 0, 0, 0.04), 0 -1px 4px 0 rgba(0, 0, 0, 0.16);

                .total-info {
                    float: left;

                    p {
                        font-weight: 600;
                        font-size: 12px;
                        color: rgba(0, 0, 0, 0.56);
                        margin-bottom: 2px;
                    }

                    h3 {
                        font-weight: 600;
                        font-size: 18px;
                        color: rgba(0, 0, 0, 0.87);
                    }
                }

                .btn {
                    float: right;
                }
            }
        }
    }
</style>