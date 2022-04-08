<template>

    <div class="product-card">
        <router-link :to="'/products/' + product.id">
            <div class="product-image">
                <img
                    alt="product-base-image-medium"
                    :src="product.base_image.medium_image_url"
                />
            </div>
        </router-link>

        <div class="product-information">

            <div class="product-price">
                <!--if there is no special price of an item-->
                <span v-html="product.formated_price"></span>
                <!--end-->
                <i class="icon compare-icon" @click="moveToCompare"></i>

                <i class="icon wishlist-icon" v-if="isCustomer == true" :class="[product.is_wishlisted ? 'filled-wishlist-icon' : '']" @click="moveToWishlist"></i>
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

        props: ['product', 'isCustomer'],

        methods: {
            moveToWishlist () {
                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/wishlist/add/' + this.product.id)
                    .then(response => {
                        this.$toasted.show(response.data.message, { type: 'success' })

                        this.product.is_saved = response.data.data ? true : false;

                        this.product.is_wishlisted = response.data.data ? true : false;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(error => {
                        this.$toasted.show(error.response.data.error, { type: 'error' })
                    });
            },

            moveToCompare () {
                EventBus.$emit('show-ajax-loader');

                if (this.isCustomer) {
                    this.$http.put('/api/pwa/comparison/', {productId: this.product.id})
                    .then(response => {
                        this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(error => {
                        this.$toasted.show(error.response.data.error, { type: 'error' })
                    });
                } else {
                    let updatedItems = [this.product.id];
                    let existingItems = JSON.parse(localStorage.getItem('compared_product'));

                    if (existingItems) {
                        debugger
                        if (existingItems.indexOf(this.product.id) == -1) {
                            updatedItems = existingItems.concat(updatedItems);

                            localStorage.setItem('compared_product', JSON.stringify(updatedItems));

                            this.$toasted.show('Item Succesfully added to compare list', { type: 'success' })

                            EventBus.$emit('hide-ajax-loader');
                        } else {
                            this.$toasted.show('Item is already added to compare list', { type: 'success' })

                            EventBus.$emit('hide-ajax-loader');
                        }
                    } else {
                        localStorage.setItem('compared_product', JSON.stringify(updatedItems));

                        this.$toasted.show('Item Succesfully added to compare list', { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');
                    }
                }
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
                    display: inline;
                    margin-left: 0px;
                }

                .icon {
                    float: right;
                    cursor: pointer;
                }
            }

            .regular-price {
                text-decoration: line-through;
                opacity: 0.56;
                font-family: 600;
                font-size: 14px;
                color: #000000;
                margin-left: 8px;
            }

            .product-name {
                font-size: 12px;
            }
        }
    }
</style>