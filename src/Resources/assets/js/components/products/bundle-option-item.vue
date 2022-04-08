<template>
    <div class="bundle-option-item">
        <div class="control-group" :class="[errors.has('bundle_options[' + option.id + '][]') ? 'has-error' : '']">
            <label :class="[option.is_required ? 'required' : '', 'option_label']">{{ option.label }}</label>

            <div v-if="option.type == 'select'">
                <select class="control" :name="'bundle_options[' + option.id + '][]'" v-model="selected_product" v-validate="option.is_required ? 'required' : ''" :data-vv-as="option.label + '&quot;'">
                    <option value="">{{ $t('bundle.choose_a_selection') }}</option>
                    <option v-for="(product, index2) in option.products" :value="product.id" :key="index2">
                        {{ product.name + ' + ' + product.price.final_price.formated_price }}
                    </option>
                </select>
            </div>

            <div v-if="option.type == 'radio'">
                <div class="radio" v-if="! option.is_required">
                    <input type="radio" :name="'bundle_options[' + option.id + '][]'" v-model="selected_product" value="0" :id="'bundle_options[' + option.id + '][]'">
                    <label class="radio-view" :for="'bundle_options[' + option.id + '][]'"></label>

                    <span>{{ $t('bundle.none') }}</span>
                </div>

                <div class="radio" v-for="(product, index2) in option.products" :key="index2">
                    <input type="radio" :name="'bundle_options[' + option.id + '][]'" v-model="selected_product" v-validate="option.is_required ? 'required' : ''" :data-vv-as="'&quot;' + option.label + '&quot;'" :value="product.id" :id="'bundle_options[' + option.id + '][]'">
                    <label class="radio-view" :for="'bundle_options[' + option.id + '][]'"></label>

                    <div class="">
                        {{ product.name }}

                        <span class="price">
                            + {{ product.price.final_price.formated_price }}
                        </span>
                    </div>
                </div>
            </div>

            <template v-if="option.type == 'checkbox'">
                <div class="checkbox" v-for="(product, index2) in option.products" :key="index2">
                    <div class="checkbox-container">
                        <input type="checkbox" :name="'bundle_options[' + option.id + '][]'" :value="product.id" v-model="selected_product" v-validate="option.is_required ? 'required' : ''" :data-vv-as="'&quot;' + option.label + '&quot;'" :id="'bundle_options[' + option.id + '][]'">
                        <label class="checkbox-view" :for="'bundle_options[' + option.id + '][]'"></label>
                    </div>

                    <div class="product-name-container">
                        <span>{{ product.name }}</span>

                        <span class="price">
                            + {{ product.price.final_price.formated_price }}
                        </span>
                    </div>
                </div>
            </template>

            <div v-if="option.type == 'multiselect'">
                <select class="control" :name="'bundle_options[' + option.id + '][]'" v-model="selected_product" v-validate="option.is_required ? 'required' : ''" :data-vv-as="'&quot;' + option.label + '&quot;'" multiple>
                    <option value="0" v-if="! option.is_required">{{ __('shop::app.products.none') }}</option>
                    <option v-for="(product, index2) in option.products" :value="product.id" :key="index2">
                        {{ product.name + ' + ' + product.price.final_price.formated_price }}
                    </option>
                </select>
            </div>

            <span class="control-error" v-if="errors.has('bundle_options[' + option.id + '][]')">
                {{ errors.first('bundle_options[' + option.id + '][]') }}
            </span>
        </div>

        <div v-if="option.type == 'select' || option.type == 'radio'">
            <div class="quantity-container">
                <label>{{ $t('Quantity') }}</label>

                <div class="quantity">
                    <button type="button" class="btn btn-black decrease-qty"
                        @click="changeQuantity('decrease', option.id)">
                        <i class="icon minus-icon"></i>
                    </button>

                    <div class="quantity-label">
                        <span :id="`qty-${option.id}`">{{ product_qty }}</span>
                        {{ $t('number Units', {number: null}) }}
                    </div>

                    <button type="button" class="btn btn-black increase-qty" @click="changeQuantity('increase', option.id)">
                        <i class="icon plus-icon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'bundle-option-item',

        props: ['index', 'option', 'formData'],

        inject: ['$validator'],

        data: function() {
            return {
                selected_product: (this.option.type == 'checkbox' || this.option.type == 'multiselect')  ? [] : null,

                qty_validations: ''
            }
        },

        computed: {
            product_qty: function() {
                return this.formData.qty_options[this.option.id][this.selected_product];
            }
        },

        watch: {
            selected_product: function (value) {
                this.qty_validations = this.selected_product ? 'required|numeric|min_value:1' : '';

                this.$emit('onProductSelected', value)
            }
        },

        created: function() {
            for (var key1 in this.option.products) {
                if (! this.option.products[key1].is_default)
                    continue;

                if (this.option.type == 'checkbox' || this.option.type == 'multiselect') {
                    this.selected_product.push(this.option.products[key1].id)
                } else {
                    this.selected_product = this.option.products[key1].id
                }
            }
        },

        methods: {
            qtyUpdated: function(qty) {
                if (! this.option.products[this.selected_product])
                    return;

                this.option.products[this.selected_product].qty = qty;
            },

            changeQuantity: function (type, optionId) {

                if (type == 'increase') {
                    this.$set(this.formData.bundle_option_qty, optionId, this.formData.qty_options[optionId][this.selected_product] + 1);
                    this.$set(this.formData.qty_options[optionId], this.selected_product, this.formData.qty_options[optionId][this.selected_product] + 1);
                } else if (type == 'decrease') {
                    if (this.formData.qty_options[optionId][this.selected_product] > 1) {
                        this.$set(this.formData.bundle_option_qty, optionId, this.formData.qty_options[optionId][this.selected_product] - 1);
                        this.$set(this.formData.qty_options[optionId], this.selected_product, this.formData.qty_options[optionId][this.selected_product] - 1);
                    }
                }

                document.getElementById(`qty-${optionId}`).innerHTML = this.formData.qty_options[optionId][this.selected_product];
            },
        }
    }
</script>