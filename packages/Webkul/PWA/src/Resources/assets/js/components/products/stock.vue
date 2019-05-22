<template>
    <div class="product-stock">

        <span class="active" v-if="product.in_stock">
            {{ $t('In Stock') }}
        </span>

        <span v-if="! product.in_stock">
            {{ $t('Out Of Stock') }}
        </span>
    </div>
</template>

<script>
    export default {
        name: 'product-stock',
        
        props: ['product']
    }
</script>

<style scoped lang="scss">
    .product-stock {
        color: #ff6472;
        font-size: 14px;
        font-weight: 700;

        .active {
            color: rgba(0, 0, 0, 0.87);
        }
    }
</style>