<template>

    <td v-if="attribute['code'] == 'name'" class="attribute-value">
        <div class="product-name">
            {{ compareItem.name }}
        </div>
    </td>

    <td v-else-if="attribute['code'] == 'product_image'" class="attribute-value">

        <router-link :to="'/products/' + compareItem.product_id">
            <div class="product-image">
                <img alt="product-base-medium-image" :src="compareItem.product_image" />
            </div>
        </router-link>
    </td>

    <td v-else-if="attribute['code'] == 'price'" class="attribute-value">
        <div class="product-price" v-html="compareItem.priceHTML">
        </div>
    </td>

    <td v-else-if="attribute['code'] == 'addToCartHtml'" class="attribute-value">
        <div class="compare-to-cart" @click="removeItem">
            {{ $t('Remove') }}
        </div>
    </td>

    <td v-else-if="attribute['code'] == 'color'" class="attribute-value">
        <div class="product-price" v-html="compareItem.color_label">
        </div>
    </td>

    <td v-else-if="attribute['code'] == 'size'" class="attribute-value">
        <div class="product-price" v-html="compareItem.size_label">
        </div>
    </td>

    <td v-else-if="attribute['code'] == 'description'" class="attribute-value">
        <div class="product-price" v-html="compareItem.description">
        </div>
    </td>

</template>

<script>
    export default {
        name: 'compare-item',

        props: ['compareItem', 'customer', 'attribute'],

        methods: {
            removeItem () {
                this.$emit('onRemove')
            },

            moveToCart () {
                this.$emit('onMoveToCart')
            }
        }
    }
</script>

<style scoped lang="scss">


        .remove-item {
            background: #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            padding: 2px;
            cursor: pointer;

            .icon {
                opacity: 0.6;
                width: 20px;
                height: 20px;
            }
        }
        .compare-to-cart {
            padding: 2px;
            background: #000;
            float: left;
            margin-top: 1px;
            margin-right: 10px;
            color: #fff;
            border: 2px solid #000;
        }

        a {
            color: #000000;
            padding: 8px;
            display: inline-block;
            width: 100%;
        }

        .product-image {
            background: #f2f2f2;

            img {
                height: 150px;
            }
        }

        .product-name {
            font-weight:bold;
        }

        .compare-action {
            padding: 12px;
            text-align: center;
            display: inline-block;
            width: 100%;
            border-top: 1px solid rgba(0, 0, 0, 0.12);
            font-weight: 600;
            font-size: 12px;
            color: #000000;
            cursor: pointer;
            text-transform: uppercase;
            height: 40px;
            position: relative;
            bottom: 0;
        }

        .attribute-value {
            padding-right:10px;
        }

</style>