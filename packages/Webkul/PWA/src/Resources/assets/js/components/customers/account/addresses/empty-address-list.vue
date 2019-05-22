<template>
    <div class="no-result-found">
        <div class="illustration empty-addresses-img"></div>

        <h2>{{ $t('No Addresses') }}</h2>

        <p>{{ $t('You didn’t add any of your addresses yet.') }}</p>

        <router-link class="btn btn-black" :to="'/customer/account/addresses/create'">{{ $t('Add New Address') }}</router-link>
    </div>
</template>

<script>
    export default {
        name: 'empty-address-list'
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