<template>
    <div class="product-social-links">
        <div class="wishlist-link" @click="moveToWishlist">
            <i class="icon wishlist-icon" :class="[isWishlisted ? 'filled-wishlist-icon' : '']"></i>

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

        data () {
			return {
				isWishlisted: false,
            }
        },

        mounted() {
            this.getWithListLstatus();
        },

        methods: {
            getWithListLstatus(){
                const token = JSON.parse(localStorage.getItem("token"));
                if (token) {
                    EventBus.$emit('show-ajax-loader');

                    this.$http.get('/api/v1/customer/wishlist')
                        .then(response => {
                            const isExits = response.data.data.find(item => item.product.id == this.$route.params.id);
                            if (isExits) {
                                this.isWishlisted = true;
                            }
                        })
                        .catch(error => {});

                    EventBus.$emit('hide-ajax-loader');
                }

            },

            moveToWishlist () {
                EventBus.$emit('show-ajax-loader');

                this.$http.post('/api/v1/customer/wishlist/' + this.$route.params.id)
                    .then(response => {

                        this.$toasted.show(response.data.message, { type: 'success' })

                        this.isWishlisted = response.data.data ? true : false;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(error => {
                        if (error.response.status == 401) {
                            this.$toasted.show(this.$t('please_login_first'), { type: 'error' })

                            this.$router.push({name: 'login-register'})
                        } else {
                            this.$toasted.show(error.response.data.error, { type: 'error' })
                        }
                    });
            },

            share () {
                if (navigator.share) {
                    navigator.share({
                        title: this.product.name,
                        text: this.product.name,
                        url: `${window.config.app_base_url}/mobile/products/${this.product.id}`,
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
