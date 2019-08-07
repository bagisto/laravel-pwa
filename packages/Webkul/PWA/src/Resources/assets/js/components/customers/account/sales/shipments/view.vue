<template>
    <div class="content">

        <custom-header :title="$t('Shipment - #', {shipment_id: $route.params.id})"></custom-header>

        <div class="shipment-details" v-if="shipment">

            <div class="shipment-items-section sale-section">
                <h2 class="sale-section-title">{{ $t('number Item(s)', {number: shipment.items.length}) }}</h2>

                <div class="shipment-item-list sale-section-content">
                    <div class="shipment-item" v-for="shipmentItem in shipment.items">
                        <div class="shipment-item-info">
                            <div class="shipment-item-name">
                                {{ shipmentItem.name }}
                            </div>

                            <div class="shipment-item-options">
                                <div class="attributes" v-if="shipmentItem.additional.attributes">
                                    <div class="option" v-for="attribute in shipmentItem.additional.attributes">
                                        <label>{{ attribute.attribute_name }} - </label>
                                        <span>{{ attribute.option_label }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="shipment-item-price">
                                <label>{{ $t('Qty Shipped -') }} </label>
                                <span>{{ shipmentItem.qty }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomHeader from '../../../../layouts/custom-header';

    export default {
        name: 'shipment-detail',

        components: { CustomHeader },

        data () {
			return {
				shipment: null,
            }
        },

        mounted () {
            this.getShipment(this.$route.params.id)
        },

        methods: {
            getShipment (shipmentId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/shipments/' + shipmentId)
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.shipment = response.data.data;
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

        .shipment-details {
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

            .shipment-items-section {
                margin-top: -1px;

                .shipment-item-list {
                    padding: 0 16px;

                    .shipment-item {
                        display: inline-block;
                        padding: 8px 0;
                        border-bottom: 1px solid rgba(0, 0, 0, 0.12);

                        &:last-child {
                            border-bottom: 0;
                        }

                        .shipment-item-info {
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

                            .shipment-item-options {
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

                            .shipment-item-review-link {
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
        }
    }
</style>