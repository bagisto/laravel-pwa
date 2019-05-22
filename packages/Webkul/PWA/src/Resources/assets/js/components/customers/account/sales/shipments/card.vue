<template>
    <div class="shipment-card">
        <div class="shipment-details">
            <label>{{ $t('Shipment') }}</label>
            <div class="shipment-id">#{{ shipment.id }}</div>

            <i class="icon sharp-arrow-right-icon"></i>
        </div>

        <div class="shipment-actions">
            <router-link class="view-link" :to="'/customer/account/' + $route.params.order_id + '/shipments/' + shipment.id">
                <i class="icon sharp-arrow-line-icon"></i>

                <span>{{ $t('View Shipment') }}</span>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'shipment-card',
        
        props: ['shipment']
    }
</script>

<style scoped lang="scss">
    .shipment-card {
        position: relative;
        background: #ffffff;
        margin-bottom: 28px;

        .shipment-details {
            padding: 16px;

            label {
                font-weight: 600;
                font-size: 19px;
                color: rgba(0, 0, 0, 0.56);
                display: block;
                margin-bottom: 11px;
            }

            .shipment-id {
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
        
        .shipment-actions {
            border-top: 1px solid rgba(0, 0, 0, 0.12);
            border-bottom: 1px solid rgba(0, 0, 0, 0.12);
            font-weight: 600;
            font-size: 14px;

            .view-link {
                text-align: center;
                width: 100%;
                display: inline-block;
                padding: 16px;
                cursor: pointer;
                color: rgba(0, 0, 0, 0.87);

                .icon {
                    margin-right: 8px;
                    opacity: 0.56;
                    vertical-align: middle;
                }

                span {
                    line-height: 24px;
                }
            }
        }
    }
</style>