<template>
    <div class="product-price">
        <div v-html="product.formated_price"></div>
    </div>
</template>

<script>
    export default {
        name: 'price',
        
        props: ['product']
    }
</script>

<style scoped lang="scss">
    .product-price {
        font-size: 20px;
        font-weight: 600;
        color: #000000;
        margin-bottom: 16px;

        .price-label {
            font-size: 12px;
            font-weight: 500;
        }

        .regular-price {
            text-decoration: line-through;
            opacity: 0.56;
            font-family: 600;
            font-size: 14px;
            color: #000000;
            margin-left: 8px;
        }
    }
</style>