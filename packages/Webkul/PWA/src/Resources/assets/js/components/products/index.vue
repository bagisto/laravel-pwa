<template>
    <div class="content" v-if="product">

        <form action="POST" @submit.prevent="validateBeforeSubmit">
            <gallery-images :product="product"></gallery-images>

            <div class="product-details">
                <div class="product-name">{{ product.name }}</div>

                <price :product="product"></price>

                <review :product="product"></review>

                <stock :product="product"></stock>
            </div>

            <social-links :product="product"></social-links>

            <div class="product-short-description">
                <span v-html="product.short_description"></span>
            </div>

            <div class="product-options-qty">
                <configurable-options
                    v-if="product.type == 'configurable'"
                    :product="product"
                    :form-data="formData">
                </configurable-options>

                <div class="quantity-container">
                    <label>{{ $t('Quantity') }}</label>

                    <div class="quantity">
                        <button type="button" class="btn btn-black decrease-qty"
                            @click="formData.quantity > 1 ? formData.quantity-- : formData.quantity">
                            <i class="icon minus-icon"></i>
                        </button>

                        <div class="quantity-label">
                            {{ $t('number Units', {number: formData.quantity}) }}
                        </div>

                        <button type="button" class="btn btn-black increase-qty" @click="formData.quantity++">
                            <i class="icon plus-icon"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="add-to-buttons">
                <button type="submit" class="add-to-cart-btn">{{ $t('Add To Cart') }}</button>

                <button class="btn btn-black buy-now-btn" @click="buyNow()">{{ $t('Buy Now') }}</button>
            </div>

            <div class="product-description">
                <accordian :title="'Details'" :active="false">
                    <div slot="body">

                        <span v-html="product.description"></span>

                    </div>
                </accordian>
            </div>

            <attributes :product="product"></attributes>

            <reviews v-if="product.reviews.total" :product="product"></reviews>
        </form>
    </div>
</template>

<script>
    import Accordian           from '../shared/accordian';
    import GalleryImages       from './gallery-images';
    import Price               from './price';
    import Review              from './review';
    import Stock               from './stock';
    import SocialLinks         from './social-links';
    import ConfigurableOptions from './configurable-options';
    import Reviews             from './reviews';
    import Attributes          from './attributes';

    export default {
        name: 'product',

        components: {
            Accordian,
            GalleryImages,
            Price,
            Review,
            Stock,
            SocialLinks,
            ConfigurableOptions,
            Reviews,
            Attributes
        },

        data () {
			return {
				product: null,

                formData: {
                    product: this.$route.params.id,

                    quantity: 1,

                    super_attribute: {},

                    selected_configurable_option: 0
                }
            }
        },

        watch: {
            '$route.params.id': function (id) {
                this.getProduct(id);
            }
        },

        mounted () {
            this.getProduct(this.$route.params.id);
        },

        methods: {
            getProduct (productId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/products/' + productId)
                    .then(function(response) {
                        this_this.product = response.data.data;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            },

            validateBeforeSubmit () {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.addToCart()
                    }
                });
            },

            addToCart () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.post("/api/checkout/cart/add/" + this.$route.params.id, this.formData)
                    .then(function(response) {
                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        EventBus.$emit('checkout.cart.changed', response.data.data);

                        // this_this.$router.push({name: 'cart'})
                    })
                    .catch(function (error) {
                    })
            },

            buyNow () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.post("/api/checkout/cart/add/" + this.$route.params.id, this.formData)
                    .then(function(response) {

                        EventBus.$emit('hide-ajax-loader');

                        EventBus.$emit('checkout.cart.changed', response.data.data);

                        this_this.$router.push({name: 'cart'})
                    })
                    .catch(function (error) {
                    })
            }
        }
    }
</script>

<style lang="scss">
    .product-details {
        margin-bottom: 0;
        padding: 16px;
        background: #ffffff;

        .product-name {
            font-size: 14px;
            margin-bottom: 10px;
        }
    }

    .product-short-description {
        padding: 16px;
        background: #ffffff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
    }

    .product-options-qty {
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        padding: 16px;
        background: #ffffff;
        margin-bottom: 12px;
        width: 100%;
        display: inline-block;
        position: relative;

        .quantity-container {
            label {
                display: block;
                font-weight: 700;
                font-size: 12px;
                color: rgba(0, 0, 0, 0.56);
                margin-bottom: 16px;
            }

            .quantity {
                position: relative;

                .btn {
                    padding: 16px 24px;

                    &.decrease-qty {
                        float: left;
                    }

                    &.increase-qty {
                        float: right;
                    }
                }

                .quantity-label {
                    position: absolute;
                    left: 0;
                    right: 0;
                    top: 24px;
                    color: rgba(0, 0, 0, 0.87);
                    opacity: 0.87;
                    font-size: 16px;
                    text-align: center;
                    pointer-events: none;
                }
            }
        }
    }

    .add-to-buttons {
        padding: 6px;
        background: #ffffff;
        margin-bottom: 12px;
        width: 100%;
        display: inline-block;
        box-shadow: 0 -10px 10px 0 rgba(0, 0, 0, 0.04), 0 -1px 4px 0 rgba(0, 0, 0, 0.16);

        .add-to-cart-btn {
            font-size: 14px;
            color: rgba(0, 0, 0, 0.56);
            background: #ffffff;
            border: 0;
            font-weight: 500;
            padding: 15px 0;
            text-transform: uppercase;
            float: left;
            width: 50%;
            text-align: center;
            cursor: pointer;
        }

        .buy-now-btn {
            float: right;
            width: 50%;
        }
    }
</style>