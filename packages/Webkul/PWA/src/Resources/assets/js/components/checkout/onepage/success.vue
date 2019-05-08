<template>
    <div class="order-success">
        <div class="illustration order-success-img"></div>

        <h2>Thanks for Purchase</h2>

        <p>Your order number is: <span class="order-id">#{{ orderId }}</span></p>

        <p>We'll email you an order confirmation with details and tracking info.</p>

        <router-link class="btn btn-black" :to="'/'">Continue Shopping</router-link>
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

            .order-id {
                font-weight: 700;
                color: rgba(0, 0, 0, 0.86);
            }
        }

        .btn {
            margin-top: 24px;
        }
    }
</style>