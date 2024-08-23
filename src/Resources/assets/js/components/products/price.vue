<template>
    <div v-if="product.type == 'bundle'" class="product-price">
            <p class="">
                <span v-if="bunderPrice.minimum.final.price != bunderPrice.minimum.regular.price" class="regular-price"> {{bunderPrice.minimum.regular.formatted_price}} </span> {{bunderPrice.minimum.final.formatted_price}}
            </p>
            <p class="price-differencer">To</p>
            <p>
                <span v-if="bunderPrice.maximum.final.price != bunderPrice.maximum.regular.price" class="regular-price"> {{bunderPrice.maximum.regular.formatted_price}} </span> {{bunderPrice.maximum.final.formatted_price}}
            </p>
    </div>
    <div v-else-if="product.type == 'grouped'" class="product-price">
        <div>
            <span class="price-label">{{ $t('Stating at') }}</span> {{product.formatted_price}}
        </div>
    </div>
    <div v-else-if="product.type == 'configurable'" class="product-price">
        <div>
            <span class="price-label">{{ $t('As low as') }}</span> <span class="final-price">{{product.formatted_price}}</span>
        </div>
    </div>
    <div v-else class="product-price">
        <span v-if="product.formatted_regular_price" class="regular-price">{{ product.formatted_regular_price }}</span> {{product.formatted_price}}
    </div>
</template>

<script>
    export default {
        name: 'price',

        props: ['product'],

        data () {
            return {
                bunderPrice: {
                    minimum:{
                        regular: {
                            price: null,
                            formatted_price: '',
                        },
                        final: {
                            price: null,
                            formatted_price: '',
                        },
                    },
                    maximum:{
                        regular: {
                            price: null,
                            formatted_price: '',
                        },
                        final: {
                            price: null,
                            formatted_price: '',
                        },
                    }
                }
            }
        },

        mounted() {
            console.log('type', this.product.type);

            if (this.product.type == "bundle") {

                this.CalcualtedBundelMinimumAndMaximumPrice();
            }
        },

        methods: {
            CalcualtedBundelMinimumAndMaximumPrice(){
                let bundelOptions = this.product.bundle_options.options;
                let currencySymbol = window.config.currentCurrency.symbol;

                let data = [];
                let maxRegularPrice = 0;
                let maxFinalPrice = 0;
                let minRegularPrice = 0;
                let minFinalPrice = 0;

                bundelOptions.forEach( (option, j) => {
                    let type = option.type;
                    let is_required = option.is_required;

                    if (is_required) {
                        minRegularPrice = 0;
                        minFinalPrice = 0;
                    }

                    option.products.forEach((product, i) => {
                        let single_bundle_price = (product.price.final.price * product.qty);

                        if (minFinalPrice == 0) {
                            minFinalPrice = single_bundle_price;
                        }
                        if ( single_bundle_price < minFinalPrice ) {
                            minRegularPrice = product.price.regular.price * product.qty;
                            minFinalPrice = product.price.final.price * product.qty;
                        }

                        if ( type == 'checkbox' ) {
                            maxRegularPrice += (product.price.regular.price * product.qty);
                            maxFinalPrice += (product.price.final.price * product.qty);
                        }

                        if ( type == 'radio' ) {
                            if ( (product.price.final.price * product.qty) > maxFinalPrice ) {
                                maxRegularPrice += (product.price.regular.price * product.qty);
                                maxFinalPrice = (product.price.final.price * product.qty);
                            }
                        }
                    });

                    data[j] = {
                        is_required: is_required,
                        minimum : {
                            regular:minRegularPrice,
                            final: minFinalPrice
                        },
                        maximum: {
                            regular:maxRegularPrice,
                            final: maxFinalPrice
                        },
                    }
                });

                if (data.length) {
                    const filterData = data.filter(item => item.is_required === 1);

                    if (filterData.length) {
                        minRegularPrice  = 0;
                        minFinalPrice = 0 ;

                        filterData.forEach(e => {
                            minRegularPrice += e.minimum.regular;
                            minFinalPrice += e.minimum.final;
                        });
                    }
                }

                this.bunderPrice.minimum = {
                    regular: {
                        price: minRegularPrice,
                        formatted_price: `${currencySymbol}${minRegularPrice}`
                    },
                    final: {
                        price: minFinalPrice,
                        formatted_price: `${currencySymbol}${minFinalPrice}`
                    }
                }

                this.bunderPrice.maximum = {
                    regular: {
                        price: maxRegularPrice,
                        formatted_price: `${currencySymbol}${maxRegularPrice}`
                    },
                    final: {
                        price: maxFinalPrice,
                        formatted_price: `${currencySymbol}${maxFinalPrice}`
                    }
                };
            }
        },
    }
</script>

<style scoped lang="scss">
    .product-price {
        font-size: 18px;
        font-weight: 600;
        color: #000000;
        margin-bottom: 16px;

        .price-label {
            font-size: 14px;
            font-weight: 500;
            color:#0000008f;
        }

        .price-differencer{
            color: #979797;
            font-size: 15px;
            margin-left: 11px;
        }

        .regular-price {
            text-decoration: line-through;
            opacity: 0.56;
            font-family: 600;
            font-size: 14px;
            color: #000000;
            padding-right:5px;
        }
    }
</style>
