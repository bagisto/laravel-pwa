<template>
    <div class="no-result-found">
        <div class="illustration empty-cart-img"></div>

        <h2>{{ $t('Empty Cart') }}</h2>

        <p>{{ $t('You have no items in your cart.') }}</p>

        <router-link class="btn btn-black" :to="'/'">{{ $t('Start Shopping') }}</router-link>
    </div>
</template>

<script>
    export default {
        name: 'empty-cart'
    }
</script>

<style scoped lang="scss">
    .no-result-found {
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