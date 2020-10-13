<template>
    <div class="order-success">
        <div class="illustration order-success-img"></div>

        <h2>{{ $t('Thank you for your order') }}</h2>

        <p v-html="$t('Your order number is: order_id', {order_id: '<span class=\'order-id\' style=\'font-weight: 700; color: rgba(0, 0, 0, 0.86);\'>#' + orderId + '</span>'})"></p>

        <p>{{ $t('We will email you an order confirmation with details and tracking info.') }}</p>

        <router-link class="btn btn-black" :to="'/'">{{ $t('Continue Shopping') }}</router-link>
    </div>
</template>

<script>
    export default {
        name: 'order-success',

        data () {
			return {
				orderId: this.$route.params.id
            }
        }
    };
</script>

<style scoped lang="scss">
    .order-success {
        text-align: center;
        padding-top: 100px;
        padding-bottom: 100px;

        h2 {
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        p {
            font-size: 14px;
            color: rgba(0, 0, 0, 0.56);
        }

        .btn {
            margin-top: 24px;
        }
    }
</style>