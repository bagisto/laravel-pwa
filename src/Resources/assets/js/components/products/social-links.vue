<template>
    <div class="product-social-links">
        <div class="wishlist-link" @click="moveToWishlist">
            <i class="icon wishlist-icon" :class="[product.is_saved ? 'filled-wishlist-icon' : '']"></i>

            <span>{{ $t('Wishlist') }}</span>
        </div>

        <div class="share-link" @click="share">
            <i class="icon share-icon"></i>

            <span>{{ $t('Share') }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'social-links',
        
        props: ['product'],

        methods: {
            moveToWishlist () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/wishlist/add/' + this.$route.params.id)
                    .then(function(response) {
                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        this_this.product.is_saved = response.data.data ? true : false;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {
                        this_this.$toasted.show(error.response.data.error, { type: 'error' })
                    });
            },

            share () {
                if (navigator.share) {
                    navigator.share({
                        title: this.product.name,
                        text: 'Check out this awesome product!',
                        url: window.config.app_base_url + '/products/' + this.product.url_key,
                    })
                    .then(() => console.log('Successful share'))
                    .catch((error) => console.log('Error sharing', error));
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .product-social-links {
        background: #ffffff;
        border-top: 1px solid rgba(0, 0, 0, 0.12);
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        margin-bottom: 12px;
        opacity: 0.87;
        color: #000000;
        font-weight: 600;
        font-size: 14px;

        .share-link, .wishlist-link {
            width: 50%;
            display: inline-block;
            padding: 16px;
            cursor: pointer;

            .icon {
                float: left;
                margin-right: 8px;
            }

            span {
                line-height: 24px;
            }
        }

        .wishlist-link {
            float: left;
        }
    }
</style>