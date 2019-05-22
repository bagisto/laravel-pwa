<template>
    <div class="product-card">
        <router-link :to="'/products/' + product.id">
            <div class="product-image">
                <img :src="product.base_image.medium_image_url" />
            </div>
        </router-link>

        <div class="product-information">

            <div class="product-price">
                <span>{{ product.formated_price }}</span>

                <i class="icon wishlist-icon" :class="[product.is_saved ? 'filled-wishlist-icon' : '']" @click="moveToWishlist"></i>
            </div>

            <router-link :to="'/products/' + product.id">
                <div class="product-name">
                    {{ product.name }}
                </div>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'product-card',

        props: ['product'],

        methods: {
            moveToWishlist () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/wishlist/add/' + this.product.id)
                    .then(function(response) {
                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        this_this.product.is_saved = response.data.data ? true : false;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {
                        this_this.$toasted.show(error.response.data.error, { type: 'error' })
                    });
            },
        }
    }
</script>

<style scoped lang="scss">
    .product-card {
        width: 100%;

        a {
            color: #000000;
        }

        .product-image {
            background: #f2f2f2;
            
            img {
                width: 100%;
                height: 100%;
                float: left;
            }
        }

        .product-information {
            float: left;
            width: 100%;
            margin-top: 8px;

            .product-price {
                color: rgba(0, 0, 0, 1);
                font-weight: 600;
                display: inline-block;
                width: 100%;

                span {
                    float: left;
                    margin-top: 5px;
                }

                .icon {
                    float: right;
                    cursor: pointer;
                }
            }
            
            .product-name {
                font-size: 12px;
            }
        }
    }
</style>