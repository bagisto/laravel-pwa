<template>
    <div class="product-price">
        <span v-if="
            product.type == 'grouped'
            || product.type == 'bundle'
            || product.type == 'booking'
            || product.type == 'configurable'
            || product.type == 'downloadable'
        ">
            <span class="final-price" v-html="product.formated_price"></span>
        </span>

        <span v-else>
            <span v-if="product.special_price">
                <span class="special-price">{{ product.formated_special_price }}</span>

                <span class="regular-price">{{ product.formated_price }}</span>
            </span>

            <span v-if="! product.special_price">{{ product.formated_price }}</span>
        </span>
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