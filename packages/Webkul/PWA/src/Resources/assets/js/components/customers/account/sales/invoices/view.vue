<template>
    <div class="content">

        <custom-header :title="'Invoice - #' + $route.params.id"></custom-header>

        <div class="invoice-details" v-if="invoice">

            <div class="invoice-items-section sale-section">
                <h2 class="sale-section-title">{{ invoice.items.length }} Item(s)</h2>

                <div class="invoice-item-list sale-section-content">
                    <div class="invoice-item" v-for="invoiceItem in invoice.items">
                        <div class="invoice-item-info">
                            <div class="invoice-item-name">
                                {{ invoiceItem.name }}
                            </div>

                            <div class="invoice-item-options">
                                <div class="attributes" v-if="invoiceItem.additional.attributes">
                                    <div class="option" v-for="attribute in invoiceItem.additional.attributes">
                                        <label>{{ attribute.attribute_name }} - </label>
                                        <span>{{ attribute.option_label }}</span>
                                    </div>
                                </div>

                                <label>Qty - </label>
                                <span>{{ invoiceItem.qty }}</span>
                            </div>

                            <div class="invoice-item-price">
                                <label>Price - </label>
                                <span>{{ invoiceItem.formated_price }}</span>
                            </div>

                            <div class="invoice-item-total">
                                <label>Sub Total - </label>
                                <span>{{ invoiceItem.formated_grand_total }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="total-sale-section sale-section">
                <h2 class="sale-section-title">Price Details</h2>

                <div class="invoice-totals sale-section-content">
                    <table class="sale-summary">
                        <tbody>
                            <tr>
                                <td>Subtotal</td>
                                <td>{{ invoice.formated_sub_total }}</td>
                            </tr>

                            <tr>
                                <td>Shipping and Handling</td>
                                <td>{{ invoice.formated_shipping_amount }}</td>
                            </tr>

                            <tr>
                                <td>Tax</td>
                                <td>{{ invoice.formated_tax_amount }}</td>
                            </tr>

                            <tr class="bold last">
                                <td>Grand Total</td>
                                <td>{{ invoice.formated_grand_total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="print-invoice-section sale-section">
                <router-link class="sale-section-content" :to="'/customer/account/' + $route.params.order_id + '/invoices/' + invoice.id">
                    <i class="icon sharp-save-icon"></i>

                    <span>Save Invoice</span>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomHeader from '../../../../layouts/custom-header';

    export default {
        name: 'invoice-detail',

        components: { CustomHeader },

        data () {
			return {
				invoice: null,
            }
        },

        mounted () {
            this.getInvoice(this.$route.params.id)
        },

        methods: {
            getInvoice (invoiceId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/invoices/' + invoiceId)
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.invoice = response.data.data;
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

        .invoice-details {
            top: 56px;
            position: absolute;
            width: 100%;
            z-index: 10;

            .sale-section {
                border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                margin-bottom: 12px;
                background: #ffffff;

                .sale-section-title {
                    font-size: 12px;
                    font-weight: 700;
                    color: rgba(0, 0, 0, 0.56);
                    padding: 16px;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                    text-transform: uppercase;
                }

                .sale-section-content {
                    padding: 16px;
                }
            }

            .invoice-items-section {
                margin-top: -1px;
                    
                .invoice-item-list {
                    padding: 0 16px;

                    .invoice-item {
                        display: inline-block;
                        padding: 8px 0;
                        border-bottom: 1px solid rgba(0, 0, 0, 0.12);

                        &:last-child {
                            border-bottom: 0;
                        }

                        .invoice-item-info {
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
                            }

                            .invoice-item-options {
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

                            .invoice-item-review-link {
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
                .invoice-totals {
                    padding-top: 0;
                    padding-bottom: 0;
                
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

            .print-invoice-section {
                text-align: center;

                a {
                    display: block;
                    color: rgba(0, 0, 0, 0.87);
                    font-size: 14px;
                    font-weight: 600;
                    text-transform: uppercase;

                    .icon {
                        margin-right: 8px;
                        opacity: 0.56;
                        vertical-align: middle;
                    }

                    span {
                        vertical-align: middle;
                    }
                }
            }
        }
    }
</style>