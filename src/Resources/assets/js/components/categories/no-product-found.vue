<template>
    <div class="no-result-found">
        <div class="illustration empty-category-img"></div>

        <h2>{{ $t('Oops!') }}</h2>

        <p>{{ $t('No products found here') }}</p>
    </div>
</template>

<script>
    export default {
        name: 'no-product-found',

        props: ['category']
    }
</script>

<style lang="scss">
    .no-result-found {
        text-align: center;
        padding-top: 100px;
        padding-bottom: 100px;

        h2 {
            margin-bottom: 8px;
        }

        p {
            font-size: 14px;
            color: rgba(0, 0, 0, 0.56);
        }
    }
</style>