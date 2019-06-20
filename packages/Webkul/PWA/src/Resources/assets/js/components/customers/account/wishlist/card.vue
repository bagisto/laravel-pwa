<template>
    <div class="product-card">
        <span class="remove-item" @click="removeItem">
            <i class="icon sharp-cross-icon"></i>
        </span>

        <router-link :to="'/products/' + wishlistItem.product.id">
            <div class="product-image">
                <img alt="product-base-medium-image" :src="wishlistItem.product.base_image.medium_image_url" />
            </div>

            <div class="product-information">

                <div class="product-price">
                    {{ wishlistItem.product.formated_price }}
                </div>

                <div class="product-name">
                    {{ wishlistItem.product.name }}
                </div>

            </div>
        </router-link>

        <div class="wishlist-action" @click="moveToCart">
            {{ $t('Move To Cart') }}
        </div>
    </div>
</template>

<script>
    export default {
        name: 'wishlist-item',

        props: ['wishlistItem'],

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
    .product-card {
        width: 100%;
        box-shadow: 0 10px 10px 0 rgba(0,0,0,0.04), 0 1px 4px 0 rgba(0,0,0,0.16);
        position: relative;
        padding-bottom: 40px;

        .remove-item {
            position: absolute;
            background: #fff;
            border-radius: 50%;
            right: 12px;
            top: 12px;
            box-shadow: 0 10px 10px 0 rgba(0,0,0,0.04), 0 1px 4px 0 rgba(0,0,0,0.16);
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

        a {
            color: #000000;
            padding: 8px;
            display: inline-block;
            width: 100%;
        }

        .product-image {
            background: #f2f2f2;

            img {
                width: 100%;
                height: 100%;
            }
        }

        .product-information {
            float: left;
            width: 100%;
            margin-top: 8px;

            .product-price {
                color: rgba(0, 0, 0, 1);
                font-weight: 600;
                margin-bottom: 8px;
            }

            .product-name {
                font-size: 12px;
            }
        }

        .wishlist-action {
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
            position: absolute;
            bottom: 0;
        }
    }
</style>