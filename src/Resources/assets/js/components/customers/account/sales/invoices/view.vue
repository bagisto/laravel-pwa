<template>
    <div class="content">

        <custom-header :title="$t('Invoice - #invoice_id', {invoice_id: $route.params.id})"></custom-header>

        <div class="invoice-details" v-if="invoice">

            <div class="invoice-items-section sale-section">
                <h2 class="sale-section-title">{{ $t('number Item(s)', {number: invoice.items.length}) }}</h2>

                <div class="invoice-item-list sale-section-content">
                    <div class="invoice-item">

                        <div v-for="(invoiceItem, index) in invoice.items" :key="index">

                            <div class="invoice-item-info">
                                <div class="invoice-item-name">
                                    {{ invoiceItem.name }}
                                </div>
                                <div class="invoice-item-name">
                                    <i>{{ invoiceItem.sku }}</i>
                                </div>

                                <div class="invoice-item-options">
                                    <div class="attributes" v-if="invoiceItem.additional.attributes">
                                        <div class="option" v-for="(attribute, attributeIndex) in invoiceItem.additional.attributes" :key="attributeIndex">
                                            <label>{{ attribute.attribute_name }} - </label>
                                            <span>{{ attribute.option_label }}</span>
                                        </div>
                                    </div>

                                    <label>{{ $t('Qty -') }} </label>
                                    <span>{{ invoiceItem.qty }}</span>
                                </div>

                                <div class="invoice-item-price">
                                    <label>{{ $t('Price -') }} </label>
                                    <span>{{ invoiceItem.formatted_price }}</span>
                                </div>

                                <div class="invoice-item-total">
                                    <label>{{ $t('Sub Total -') }} </label>
                                    <span>{{ invoiceItem.formatted_grand_total }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="total-sale-section sale-section">
                <h2 class="sale-section-title">{{ $t('Price Details') }}</h2>

                <div class="invoice-totals sale-section-content">
                    <table class="sale-summary">
                        <tbody>
                            <tr>
                                <td>{{ $t('Subtotal') }}</td>
                                <td>{{ invoice.formatted_sub_total }}</td>
                            </tr>

                            <tr>
                                <td>{{ $t('Shipping and Handling') }}</td>
                                <td>{{ invoice.formatted_shipping_amount }}</td>
                            </tr>

                            <tr>
                                <td>{{ $t('Tax') }}</td>
                                <td>{{ invoice.formatted_tax_amount }}</td>
                            </tr>

                            <tr class="bold last">
                                <td>{{ $t('Grand Total') }}</td>
                                <td>{{ invoice.formatted_grand_total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="print-invoice-section sale-section">
                <div class="sale-section-content" @click="printInvoice(invoice.id)">
                    <i class="icon sharp-save-icon"></i>

                    <span>{{ $t('Save Invoice') }}</span>
                </div>
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
                EventBus.$emit('show-ajax-loader');

                this.$http.get(`/api/v1/customer/invoices/${invoiceId}`)
                    .then(response => {
                        this.invoice = response.data.data;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(error => {});
            },

            printInvoice (invoiceId) {
                this.$http.get(`/api/pwa/print/Invoice/${invoiceId}`, { responseType: 'blob'})
                    .then(response => {
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');

                        link.href = url;

                        link.setAttribute('download', 'invoice.pdf'); //or any other extension
                        document.body.appendChild(link);
                        link.click();
                    })
                    .catch(error => {});
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
                    cursor: pointer;
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
                            margin-bottom: 15px;
                            border-bottom: 1px dotted #CCC;

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

                            .invoice-item-name{
                                font-weight:600;
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
