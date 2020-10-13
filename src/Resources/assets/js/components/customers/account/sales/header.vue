<template>
    <div>
        <custom-header>
            <div slot="content">
                <div class="order-menu">
                    <h2>
                        {{ title }}

                        <i class="icon arrow-bottom-white-icon" @click="toggleHeader"></i>
                    </h2>

                    <ul>
                        <li :class="[active == 'order' ? 'active': '']">
                            <router-link :to="'/customer/account/orders/' + orderId">{{ $t('Order Details') }}</router-link>
                        </li>
                        <li :class="[active == 'invoice' ? 'active': '']">
                            <router-link :to="'/customer/account/' + orderId + '/invoices'">{{ $t('Order Invoices') }}</router-link>
                        </li>
                        <li :class="[active == 'shipment' ? 'active': '']">
                            <router-link :to="'/customer/account/' + orderId + '/shipments'">{{ $t('Order Shipments') }}</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </custom-header>
    </div>
</template>

<script>
    import CustomHeader from '../../../layouts/custom-header';

    export default {
        name: 'sales-header',

        props: ['title', 'active', 'orderId'],

        components: { CustomHeader },

        data () {
			return {
                isMenuExpanded: false,
            }
        },

        methods: {
            toggleHeader () {
                this.isMenuExpanded = ! this.isMenuExpanded;

                this.$emit('onHeaderToggle', this.isMenuExpanded)
            }
        }
    }
</script>

<style scoped lang="scss">
    .order-menu {
        padding: 16px 8px;
        color: #ffffff;

        h2 {
            padding-left: 66px;
        }

        ul {
            margin-top: 7px;
            margin-top: 23px;
            display: none;

            li {
                &.active {
                    background: rgba(255, 255, 255, 0.16);
                }

                a {
                    font-weight: 600;
                    font-size: 14px;
                    color: rgba(255, 255, 255, 0.87);
                    padding: 15px 20px;
                    display: block;
                }
            }
        }
    }

    .expanded {
        .order-menu {
            ul {
                display: block;
            }
        }
    }
</style>