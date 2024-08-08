<template>
    <div class="content" v-if="product">

        <gallery-images :product="product"></gallery-images>

        <form action="POST" @submit.prevent="validateBeforeSubmit">
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

                <grouped-products-options
                    v-if="product.type == 'grouped'"
                    :product="product"
                    :form-data="formData">
                </grouped-products-options>

                <bundle-options
                    v-if="product.type == 'bundle'"
                    :product="product"
                    :form-data="formData">
                </bundle-options>

                <downloadable-options
                    v-if="product.type == 'downloadable'"
                    :product="product"
                    :form-data="formData">
                </downloadable-options>

                <booking-options
                    v-if="product.type == 'booking'"
                    :product="product"
                    :form-data="formData">
                </booking-options>

                <div class="quantity-container" v-if="product.show_quantity_changer">
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

                <button class="btn btn-black buy-now-btn" @click.prevent="buyNow" >{{ $t('Buy Now') }}</button>
            </div>

            <div class="product-description">
                <accordian :title="'Details'" :active="true">
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
    import Stock                    from './stock';
    import Price                    from './price';
    import Review                   from './review';
    import Reviews                  from './reviews';
    import Attributes               from './attributes';
    import SocialLinks              from './social-links';
    import GalleryImages            from './gallery-images';
    import BundleOptions            from './bundle-options';
    import BookingOptions           from './booking-options';
    import Accordian                from '../shared/accordian';
    import ConfigurableOptions      from './configurable-options';
    import DownloadableOptions      from './downloadable-options';
    import GroupedProductsOptions   from './grouped-products-options';

    export default {
        name: 'product',

        components: {
            Price,
            Stock,
            Review,
            Reviews,
            Accordian,
            Attributes,
            SocialLinks,
            BundleOptions,
            GalleryImages,
            BookingOptions,
            DownloadableOptions,
            ConfigurableOptions,
            GroupedProductsOptions
        },

        data () {
			return {
				product: null,

                formData: {
                    product_id: this.$route.params.id,

                    quantity: 1,

                    is_buy_now: 0,

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
                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/pwa/products/' + productId)
                    .then(response => {
                        this.product = response.data.data;

                        if (this.product.type == "grouped") {
                            this.formData.qty = {};
                            delete(this.formData['quantity']);

                            this.product.grouped_products.forEach(product => {
                                this.$set(this.formData.qty, product.id, product.qty);
                            });
                        }

                        if (this.product.type == "downloadable") {
                            this.formData.links = {};
                        }

                        if (this.product.type == "bundle") {
                            delete(this.formData['super_attribute']);
                            delete(this.formData['selected_configurable_option']);

                            this.formData.qty_options = {};
                            this.formData.bundle_options = {};
                            this.formData.bundle_option_qty = {};

                            this.product.bundle_options.options.forEach(option => {
                                this.formData.qty_options[option.id] = {};

                                option.products.forEach((product, key) => {
                                    this.formData.qty_options[option.id][product.id] = option.products[key].qty;
                                });

                                this.formData.bundle_options[option.id] = [0];
                                this.formData.bundle_option_qty[5] = 7;
                            });
                        }

                        if (this.product.type == "booking") {
                            delete(this.formData['super_attribute']);
                            delete(this.formData['selected_configurable_option']);

                            if (this.product.booking_product.type == "event") {
                                this.formData.booking = {
                                    qty: {}
                                };

                                this.product.booking_product.tickets.forEach(ticket => {
                                    this.formData.booking.qty[ticket.id] = 0;
                                });

                            } else if(this.product.booking_product.type == "rental") {
                                if (this.product.booking_product.renting_type == "daily_hourly") {
                                    this.formData.booking = {
                                        "date": "",
                                        'renting_type': 'hourly',
                                        "slot": {
                                            "from"  : "",
                                            "to"    : "",
                                        }
                                    };
                                } else {
                                    this.formData.booking = {
                                        'date_to': '',
                                        'date_from': '',
                                    };
                                }
                            } else if(this.product.booking_product.type == "table") {
                                this.formData.booking = {
                                    "note": "",
                                    "date": "",
                                    "slot": "",
                                };
                            } else {
                                this.formData.booking = {
                                    'slot': '',
                                    'date': '',
                                };
                            }
                        }

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            },

            validateBeforeSubmit (event) {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.addToCart();
                    }
                });
            },

            addToCart (event) {
                EventBus.$emit('show-ajax-loader');

                var formData = this.formData;

                if (
                    formData
                    && formData.booking
                    && formData.booking.renting_type == "daily"
                ) {
                    delete(formData.booking.date);
                    delete(formData.booking.slot);
                }

                this.$http.post("/api/pwa/checkout/cart/add/" + this.$route.params.id, formData)
                    .then(response => {
                        this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        EventBus.$emit('checkout.cart.changed', response.data.data);

                        if (this.is_buy_now) {
                            this.$router.push({name: 'onepage'})
                        }
                    })
                    .catch(error => {
                    })
            },

            buyNow (event) {
                this.is_buy_now = 1;
                this.validateBeforeSubmit();
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