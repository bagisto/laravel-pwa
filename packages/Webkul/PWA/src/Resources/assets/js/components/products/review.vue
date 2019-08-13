<template>
    <div class="product-review">
        <span v-if="product.reviews.total">
            <span class="rating">
                {{ product.reviews.average_rating }}
                <i class="icon star-white-icon"></i>
            </span>

            <span class="total-review">
                {{ $t('number Reviews', {number: product.reviews.total}) }}
            </span>
        </span>

        <router-link :to="'/reviews/' + product.id + '/create'">
            {{ $t('Add Your Review') }}
        </router-link>
    </div>
</template>

<script>
    export default {
        name: 'price',

        props: ['product']
    }
</script>

<style scoped lang="scss">
    .product-review {
        margin-bottom: 14px;

        .rating {
            background: #000000;
            padding: 5px;
            color: #ffffff;
            font-weight: 600;
            margin-right: 8px;
            display: inline-block;

            .icon {
                vertical-align: middle;
                margin-bottom: 2px;
            }
        }

        .total-review {
            opacity: 0.56;
            font-weight: 600;
            font-size: 14px;
            color: #000000;
            margin-right: 16px;
        }

        a {
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            vertical-align: middle;
        }
    }
</style>