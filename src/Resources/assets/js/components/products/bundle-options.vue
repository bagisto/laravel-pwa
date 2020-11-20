<template>
    <div class="bundle-options-wrapper">
        <div class="bundle-option-list">
            <h3>{{ $t('bundle.customize_options') }}</h3>

            <bundle-option-item
                v-for="(option, index) in options"
                :option="option"
                :key="index"
                :index="index"
                :form-data="formData"
                @onProductSelected="productSelected(option, $event)">
            </bundle-option-item>
        </div>

        <div class="bundle-summary">
            <h3>{{ $t('bundle.your_customization') }}</h3>

            <div class="quantity-container">
                <label>{{ $t('Quantity') }}</label>

                <div class="quantity">
                    <button type="button" class="btn btn-black decrease-qty"
                        @click="changeQuantity('decrease', product.id)">
                        <i class="icon minus-icon"></i>
                    </button>

                    <div class="quantity-label">
                        <span :id="`qty-${product.id}`">{{ formData.quantity }}</span>
                        {{ $t('number Units', {number: null }) }}
                    </div>

                    <button type="button" class="btn btn-black increase-qty" @click="changeQuantity('increase', product.id)">
                        <i class="icon plus-icon"></i>
                    </button>
                </div>
            </div>

            <div class="control-group">
                <label>{{ $t('bundle.total_amount') }}</label>

                <div class="bundle-price">
                    {{ formated_total_price | currency(currency_options) }}
                </div>
            </div>

            <ul class="bundle-items">
                <li v-for="(option, index) in options" :key="index">
                    {{ option.label }}

                    <div class="selected-products">
                        <div v-for="(product, index1) in option.products" :key="index1">
                            <template v-if="product.is_default">
                                {{ product.qty + ' x ' + product.name }}
                            </template>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import BundleOptionItem from './bundle-option-item';

    export default {
        name: 'bundle-options',

        props: ['product', 'formData'],

        components: {
            BundleOptionItem
        },

        inject: ['$validator'],

        data: function() {
            return {
                config: this.product.bundle_options,

                options: [],

                currency_options: this.product.currency_options,
            }
        },

        computed: {
            formated_total_price: function() {
                var total = 0;

                for (var key in this.options) {
                    for (var key1 in this.options[key].products) {
                        if (! this.options[key].products[key1].is_default)
                            continue;

                        total += this.options[key].products[key1].qty * this.options[key].products[key1].price.final_price.price;
                    }
                }

                return total;
            }
        },

        created: function() {
            for (var key in this.config.options) {
                this.options.push(this.config.options[key])
            }
        },

        methods: {
            productSelected: function(option, value) {
                var selectedProductIds = Array.isArray(value) ? value : [value];

                for (var key in option.products) {
                    option.products[key].is_default = selectedProductIds.indexOf(option.products[key].id) > -1 ? 1 : 0;
                }

                if (Array.isArray(value)) {
                    this.formData.bundle_options[option.id] = value;
                } else {
                    this.formData.bundle_options[option.id][0] = value;
                }
            },

            changeQuantity: function (type, productId) {
                if (type == 'increase') {
                    this.$set(this.formData, 'quantity', this.formData.quantity + 1);
                } else if (type == 'decrease') {
                    if (this.formData.quantity > 1) {
                        this.$set(this.formData, 'quantity', this.formData.quantity - 1);
                    }
                }
            },
        }
    }
</script>