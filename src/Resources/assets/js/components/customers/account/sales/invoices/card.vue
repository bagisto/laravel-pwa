<template>
    <div class="invoice-card">
        <div class="invoice-details">
            <label>{{ $t('Invoice') }}</label>
            <div class="invoice-id">#{{ invoice.id }}</div>

            <router-link class="view-link" :to="'/customer/account/' + $route.params.order_id + '/invoices/' + invoice.id">
                <i class="icon sharp-arrow-right-icon"></i>
            </router-link>

        </div>

        <div class="invoice-actions">
            <router-link class="view-link" :to="'/customer/account/' + $route.params.order_id + '/invoices/' + invoice.id">
                <i class="icon sharp-invoice-icon"></i>

                <span>{{ $t('View Invoice') }}</span>
            </router-link>

            <div class="print-link" @click="printInvoice(invoice.id)">
                <i class="icon sharp-save-icon"></i>

                <span>{{ $t('Save Invoice') }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'invoice-card',

        props: ['invoice'],

        methods: {
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
    .invoice-card {
        position: relative;
        background: #ffffff;
        margin-bottom: 28px;

        .invoice-details {
            padding: 16px;

            label {
                font-weight: 600;
                font-size: 19px;
                color: rgba(0, 0, 0, 0.56);
                display: block;
                margin-bottom: 11px;
            }

            .invoice-id {
                font-weight: 600;
                font-size: 19px;
                color: rgba(0, 0, 0, 0.87);
                display: block;
                margin-bottom: 11px;
            }

            .sharp-arrow-right-icon {
                position: absolute;
                right: 16px;
                bottom: 50%;
                margin-bottom: 12px;
                opacity: 0.16;
            }
        }

        .invoice-actions {
            border: 1px solid rgba(0, 0, 0, 0.12);
            font-weight: 600;
            font-size: 14px;

            .view-link {
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

            .print-link {
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
    }
</style>
