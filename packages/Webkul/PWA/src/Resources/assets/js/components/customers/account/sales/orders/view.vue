<template>
    <div :class="['content', 'order', isMenuExpanded ? 'expanded' : '']">

        <sales-header :title="$t('Order Details')" active="order" :order-id="$route.params.id" @onHeaderToggle="toggleHeader($event)"></sales-header>

        <div class="order-details" v-if="order">
            <div class="order-info-section sale-section">
                <h2 class="sale-section-title">Order #0000123</h2>

                <div class="order-content sale-section-content">
                    <label>{{ $t('Placed On') }}</label>

                    <div class="date-status">
                        <span class="date">{{ new Date(order.created_at.date) | moment("D MMMM YYYY") }}</span>
                        <span :class="['status', order.status]">{{ order.status_label }}</span>
                    </div>
                </div>
            </div>

            <div class="order-items-section sale-section">
                <h2 class="sale-section-title">{{ $t('number Item(s) Ordered', {number: order.items.length}) }}</h2>

                <div class="order-item-list sale-section-content">
                    <div class="order-item" v-for="orderItem in order.items">
                        <div class="order-item-image">
                            <img alt="product-base-small-image" :src="orderItem.product.base_image.small_image_url"/>
                        </div>

                        <div class="order-item-info">
                            <div class="order-item-name">
                                {{ orderItem.product.name }}
                            </div>

                            <div class="order-item-options">
                                <div class="attributes" v-if="orderItem.additional.attributes">
                                    <div class="option" v-for="attribute in orderItem.additional.attributes">
                                        <label>{{ attribute.attribute_name }} - </label>
                                        <span>{{ attribute.option_label }}</span>
                                    </div>
                                </div>

                                <label>{{ $t('Qty -') }} </label>
                                <span>{{ orderItem.qty_ordered }}</span>
                            </div>

                            <div class="order-item-qty-invoiced" v-if="orderItem.qty_invoiced">
                                <label>{{ $t('Qty Invoiced -') }} </label>
                                <span>{{ orderItem.qty_invoiced }}</span>
                            </div>

                            <div class="order-item-qty-shipped" v-if="orderItem.qty_shipped">
                                <label>{{ $t('Qty Shipped -') }} </label>
                                <span>{{ orderItem.qty_shipped }}</span>
                            </div>

                            <div class="order-item-price">
                                <label>{{ $t('Price -') }} </label>
                                <span>{{ orderItem.formated_price }}</span>
                            </div>

                            <div class="order-item-total">
                                <label>{{ $t('Total -') }} </label>
                                <span>{{ orderItem.formated_grant_total }}</span>
                            </div>

                            <div class="order-item-review-link">
                                <router-link class="btn btn-black btn-sm" :to="'/reviews/' + orderItem.product.id + '/create'">
                                    <i class="icon white-post-review-icon"></i>
                                    {{ $t('Write a Review') }}
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="total-sale-section sale-section">
                <h2 class="sale-section-title">{{ $t('Price Details') }}</h2>

                <div class="order-totals sale-section-content">
                    <table class="sale-summary">
                        <tbody>
                            <tr>
                                <td>{{ $t('Subtotal') }}</td>
                                <td>{{ order.formated_sub_total }}</td>
                            </tr>

                            <tr>
                                <td>{{ $t('Shipping Handling') }}</td>
                                <td>{{ order.formated_shipping_amount }}</td>
                            </tr>

                            <tr class="border">
                                <td>{{ $t('Tax') }}</td>
                                <td>{{ order.formated_tax_amount }}</td>
                            </tr>

                            <tr class="bold">
                                <td>{{ $t('Total Paid') }}</td>
                                <td>{{ order.formated_grand_total_invoiced }}</td>
                            </tr>

                            <tr class="bold">
                                <td>{{ $t('Total Refunded') }}</td>
                                <td>{{ order.formated_grand_total_refunded }}</td>
                            </tr>

                            <tr class="bold last">
                                <td>{{ $t('Grand Total') }}</td>
                                <td>{{ order.formated_grand_total }}</td>
                            </tr>

                            <!--<tr class="bold">
                                <td>total due</td>
                                <td>-</td>
                                <td>{{ order.total_due }}</td>
                            </tr>-->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="shipping-payment-section sale-section">
                <h2 class="sale-section-title">{{ $t('Shipping and Payment Info') }}</h2>

                <div class="shipping-payment sale-section-content">
                    <div class="shipping-address">
                        <h3>{{ $t('Shipping Address') }}</h3>

                        <div class="address-deatils">
                            {{ order.shipping_address.address1.join(' ') }}</br>
                            {{ order.shipping_address.city }}</br>
                            {{ order.shipping_address.state }}</br>
                            {{ order.shipping_address.country_name + ' ' + order.shipping_address.postcode }}</br>
                            {{ order.shipping_address.phone }}
                        </div>
                    </div>

                    <div class="billing-address">
                        <h3>{{ $t('Billing Address') }}</h3>

                        <div class="address-deatils">
                            {{ order.billing_address.address1.join(' ') }}</br>
                            {{ order.billing_address.city }}</br>
                            {{ order.billing_address.state }}</br>
                            {{ order.billing_address.country_name + ' ' + order.billing_address.postcode }}</br>
                            {{ order.billing_address.phone }}
                        </div>
                    </div>

                    <div class="shipping-method">
                        <h3>{{ $t('Shipping Method') }}</h3>

                        <div class="shipping-method-deatils">
                            {{ order.shipping_title }}
                        </div>
                    </div>

                    <div class="payment-method">
                        <h3>{{ $t('Payment Method') }}</h3>

                        <div class="payment-method-deatils">
                            {{ order.payment_title }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SalesHeader from '../header';

    export default {
        name: 'order-detail',

        components: { SalesHeader },

        data () {
			return {
                isMenuExpanded: false,

				order: null,
            }
        },

        mounted () {
            this.getOrder(this.$route.params.id)
        },

        methods: {
            getOrder (orderId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/orders/' + orderId)
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.order = response.data.data;
                    })
                    .catch(function (error) {});
            },

            toggleHeader (value) {
                this.isMenuExpanded = value;
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

        .order-details {
            top: 56px;
            position: absolute;
            width: 100%;
            z-index: 10;

            .sale-section {
                padding: 16px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                margin-bottom: 12px;
                background: #ffffff;

                .sale-section-title {
                    font-size: 14px;
                    color: rgba(0, 0, 0, 0.56);
                    padding-bottom: 16px;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                }

                .sale-section-content {
                    padding: 16px 0 0 0;
                }
            }

            .order-info-section {
                border-top-left-radius: 15px;
                border-top-right-radius: 15px;

                .order-content {
                    label {
                        font-size: 14px;
                        color: rgba(0, 0, 0, 0.57);
                    }

                    .date-status {
                        margin-top: 4px;
                        display: inline-block;
                        width: 100%;

                        .date {
                            font-size: 14px;
                            color: rgba(0, 0, 0, 0.87);
                        }

                        .status {
                            float: right;
                            font-weight: 600;
                            font-size: 14px;
                            color: #FFFFFF;
                            text-align: center;
                            text-transform: uppercase;
                            padding: 3px 6px;

                            &.pending {
                                background: #FFCC00;
                            }

                            &.pending_payment {
                                background: #FFCC00;
                            }

                            &.processing {
                                background: #59A600;
                            }

                            &.completed {
                                background: #59A600;
                            }

                            &.canceled {
                                background: #FF4848;
                            }

                            &.closed {
                                background: #000000;
                            }

                            &.fraud {
                                background: #FF4848;
                            }
                        }
                    }
                }
            }

            .order-items-section {
                .order-item-list {
                    .order-item {
                        display: inline-block;
                        padding: 16px 0;

                        .order-item-image {
                            margin-right: 16px;
                            float: left;

                            img {
                                width: 96px;
                                height: 96px;
                            }
                        }

                        .order-item-info {
                            display: block;
                            overflow: hidden;

                            > div {
                                margin-bottom: 8px;
                                font-size: 14px;

                                &:last-child {
                                    margin-bottom: 0;
                                }

                                label {
                                    color: rgba(0, 0, 0, 0.56);
                                }

                                span {

                                }
                            }

                            .order-item-name {
                                margin-bottom: 12px;
                            }

                            .order-item-options {
                                .attributes {
                                    display: inline-block;

                                    .option {
                                        display: inline-block;

                                        span {
                                            margin-right: 8px;
                                        }
                                    }
                                }
                            }

                            .order-item-review-link {
                                .icon {
                                    margin-right: 6px;
                                    vertical-align: middle;
                                    display: inline-block;
                                }
                            }
                        }
                    }
                }
            }

            .total-sale-section {
                .order-totals {
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
                                    padding: 12px 0;
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
            }

            .shipping-payment-section {

                .shipping-payment {
                    padding: 8px 0 8px 0;

                    h3 {
                        font-weight: 700;
                        font-size: 12px;
                        color: rgba(0, 0, 0, 0.56);
                        padding: 8px 0 8px 0;
                    }

                    .shipping-address, .billing-address, .shipping-method {
                        margin-bottom: 17px;
                    }

                    .address-deatils, .payment-method-deatils, .shipping-method-deatils {
                        font-size: 14px;
                        color: rgba(0, 0, 0, 0.87);
                    }
                }
            }
        }

        &.expanded {
            .order-details {
                top: 207px;
            }
        }
    }
</style>