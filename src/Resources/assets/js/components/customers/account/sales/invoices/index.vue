<template>
    <div :class="['content', 'order', isMenuExpanded ? 'expanded' : '']">

        <sales-header :title="$t('Invoices')" active="invoice" :order-id="$route.params.order_id" @onHeaderToggle="toggleHeader($event)"></sales-header>

        <div class="order-details" v-if="order">
            <div class="order-info-section sale-section">
                <h2 class="sale-section-title">{{ $t('Order #order_id', {order_id: $route.params.order_id}) }}</h2>

                <div class="order-content sale-section-content">
                    <label>{{ $t('Placed On') }}</label>

                    <div class="date-status">
                        <span class="date">{{ new Date(order.created_at.date) | moment("D MMMM YYYY") }}</span>
                        <span :class="['status', order.status]">{{ order.status_label }}</span>
                    </div>
                </div>
            </div>

            <div class="invoice-list">
                <invoice-card v-for="invoice in invoices" :key='invoice.uid' :invoice="invoice"></invoice-card>
            </div>
        </div>
    </div>
</template>

<script>
    import SalesHeader from '../header';
    import InvoiceCard  from './card';

    export default {
        name: 'invoice-list',

        components: { SalesHeader, InvoiceCard },

        data () {
            return {
                isMenuExpanded: false,

				order: null,

                invoices: []
            }
        },

        props: ['customer'],

        mounted () {
            this.getOrder(this.$route.params.order_id)

            this.getInvoices(this.$route.params.order_id)
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

            getInvoices (orderId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/invoices', { params: { order_id: orderId, pagination: 0 } })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.invoices = response.data.data;
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
        }

        &.expanded {
            .order-details {
                top: 207px;
            }
        }
    }
</style>