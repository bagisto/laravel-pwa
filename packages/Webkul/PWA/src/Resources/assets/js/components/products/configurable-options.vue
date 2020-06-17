<template>
    <div class="attributes">
        <div v-for='(attribute, index) in childAttributes' class="attribute" :class="[attribute.swatch_type, errors.has('super_attribute[' + attribute.id + ']') ? 'has-error' : '']">
            <label class="required">{{ attribute.label }}</label>

            <span class="swatch-container">
                <label class="swatch"
                    v-for='(option, index) in attribute.options'
                    v-if="option.id"
                    :data-id="option.id"
                    :for="['attribute_' + attribute.id + '_option_' + option.id]">

                    <input type="radio"
                        v-validate="'required'"
                        :name="['super_attribute[' + attribute.id + ']']"
                        :id="['attribute_' + attribute.id + '_option_' + option.id]"
                        :value="option.id"
                        v-model="formData.super_attribute[attribute.id]"
                        :data-vv-as="'&quot;' + attribute.label + '&quot;'"
                        @change="configure(attribute, $event.target.value)"/>

                    <span v-if="attribute.swatch_type == 'color'" :style="{ background: option.swatch_value }"></span>

                    <img alt="option-switch-value" v-if="attribute.swatch_type == 'image'" :src="option.swatch_value" />

                    <span v-if="! attribute.swatch_type || attribute.swatch_type == 'text' || attribute.swatch_type == 'dropdown'">
                        {{ option.label }}
                    </span>

                </label>

                <span v-if="! attribute.options.length" class="no-options">
                    {{ $t('Please Select Above options') }}
                </span>
            </span>

            <span class="control-error" v-if="errors.has('super_attribute[' + attribute.id + ']')">
                {{ errors.first('super_attribute[' + attribute.id + ']') }}
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'configurable-options',

        props: ['product', 'formData'],

        data: () => ({
            config: {},

            childAttributes: [],

            selectedProductId: '',

            simpleProduct: null,

            galleryImages: []
        }),

        inject: ['$validator'],

        mounted () {
            this.getConfigurableConfig(this.product.id);
        },

        methods: {
            getConfigurableConfig (productId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/product-configurable-config/' + productId)
                    .then(function(response) {
                        this_this.config = response.data.data;

                        this_this.prepareData();

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            },

            prepareData () {
                this.galleryImages = this.product.images.slice(0)

                var config = jQuery.extend(true, {}, this.config)

                var childAttributes = this.childAttributes,
                    attributes = config.attributes.slice(),
                    index = attributes.length,
                    attribute;

                while (index--) {
                    attribute = attributes[index];

                    attribute.options = [];

                    if (index) {
                        attribute.disabled = true;
                    } else {
                        this.fillSelect(attribute);
                    }

                    attribute = Object.assign(attribute, {
                        childAttributes: childAttributes.slice(),
                        prevAttribute: attributes[index - 1],
                        nextAttribute: attributes[index + 1]
                    });

                    childAttributes.unshift(attribute);
                }
            },

            configure (attribute, value) {
                this.simpleProduct = this.getSelectedProductId(attribute, value);

                if (value) {
                    attribute.selectedIndex = this.getSelectedIndex(attribute, value);

                    if (attribute.nextAttribute) {
                        attribute.nextAttribute.disabled = false;

                        this.fillSelect(attribute.nextAttribute);

                        this.resetChildren(attribute.nextAttribute);
                    } else {
                        this.formData['selected_configurable_option'] = attribute.options[attribute.selectedIndex].allowedProducts[0];
                        // this.selectedProductId = attribute.options[attribute.selectedIndex].allowedProducts[0];
                    }
                } else {
                    attribute.selectedIndex = 0;

                    this.resetChildren(attribute);

                    this.clearSelect(attribute.nextAttribute)
                }

                this.reloadPrice();

                this.changeProductImages();
            },

            getSelectedIndex (attribute, value) {
                var selectedIndex = 0;

                attribute.options.forEach(function(option, index) {
                    if (option.id == value) {
                        selectedIndex = index;
                    }
                })

                return selectedIndex;
            },

            getSelectedProductId (attribute, value) {
                var options = attribute.options,
                    matchedOptions;

                matchedOptions = options.filter(function (option) {
                    return option.id == value;
                });

                if (matchedOptions[0] != undefined && matchedOptions[0].allowedProducts != undefined) {
                    return matchedOptions[0].allowedProducts[0];
                }

                return undefined;
            },

            fillSelect (attribute) {
                var options = this.getAttributeOptions(attribute.id),
                    prevOption,
                    index = 1,
                    allowedProducts,
                    i,
                    j;

                this.clearSelect(attribute)

                attribute.options = [{ 'id': '', 'label': this.config.chooseText, 'products': [] }];

                if (attribute.prevAttribute) {
                    prevOption = attribute.prevAttribute.options[attribute.prevAttribute.selectedIndex];
                }

                if (options) {
                    for (i = 0; i < options.length; i++) {
                        allowedProducts = [];

                        if (prevOption) {
                            for (j = 0; j < options[i].products.length; j++) {
                                if (prevOption.products && prevOption.products.indexOf(options[i].products[j]) > -1) {
                                    allowedProducts.push(options[i].products[j]);
                                }
                            }
                        } else {
                            allowedProducts = options[i].products.slice(0);
                        }

                        if (allowedProducts.length > 0) {
                            options[i].allowedProducts = allowedProducts;

                            attribute.options[index] = options[i];

                            index++;
                        }
                    }
                }
            },

            resetChildren (attribute) {
                if (attribute.childAttributes) {
                    attribute.childAttributes.forEach(function (set) {
                        set.selectedIndex = 0;
                        set.disabled = true;
                    });
                }
            },

            clearSelect: function (attribute) {
                if (! attribute)
                    return;

                if (! attribute.swatch_type || attribute.swatch_type == '' || attribute.swatch_type == 'dropdown') {
                    var element = document.getElementById("attribute_" + attribute.id);

                    if (element) {
                        element.selectedIndex = "0";
                    }
                } else {
                    var elements = document.getElementsByName('super_attribute[' + attribute.id + ']');

                    var this_this = this;

                    elements.forEach(function(element) {
                        element.checked = false;
                    })
                }
            },

            getAttributeOptions (attributeId) {
                var this_this = this,
                    options;

                this.config.attributes.forEach(function(attribute, index) {
                    if (attribute.id == attributeId) {
                        options = attribute.options;
                    }
                })

                return options;
            },

            reloadPrice () {
                var selectedOptionCount = 0;

                this.childAttributes.forEach(function(attribute) {
                    if (attribute.selectedIndex) {
                        selectedOptionCount++;
                    }
                });

                var priceLabelElement = document.querySelector('.price-label');
                var priceElement = document.querySelector('.final-price');

                if (this.childAttributes.length == selectedOptionCount) {
                    priceLabelElement.style.display = 'none';

                    priceElement.innerHTML = this.config.variant_prices[this.simpleProduct].final_price.formated_price;

                    EventBus.$emit('configurable-variant-selected-event', this.simpleProduct)
                } else {
                    priceLabelElement.style.display = 'inline-block';

                    priceElement.innerHTML = this.config.regular_price.formated_price;

                    EventBus.$emit('configurable-variant-selected-event', 0)
                }
            },

            changeProductImages () {
                var this_this = this;
                this.product.images.splice(0, this.product.images.length)

                this.galleryImages.forEach(function(image) {
                    this_this.product.images.push(image)
                });

                if (this.simpleProduct) {
                    this.config.variant_images[this.simpleProduct].forEach(function(image) {
                        this_this.product.images.unshift(image)
                    });
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    .attributes {
        display: block;
        width: 100%;

        .attribute {
            margin-bottom: 20px;

            > label {
                display: block;
                font-weight: 700;
                font-size: 12px;
                color: rgba(0, 0, 0, 0.56);
                margin-bottom: 16px;
            }

            &.has-error {
                .control-error {
                    color: #ff6472;
                    display: block;
                    margin-top: 5px;
                }
            }

            .swatch-container {
                display: inline-block;

                .swatch {
                    display: inline-block;
                    margin-right: 8px;
                    min-width: 44px;
                    height: 32px;

                    span {
                        min-width: 44px;
                        height: 32px;
                        float: left;
                        line-height: 32px;
                        text-align: center;
                        cursor: pointer;
                        padding: 0 10px;
                        background: #F5F5F5;
                        font-size: 14px;
                        font-weight: 600;
                        color: #000000;
                    }

                    img {
                        width: 48px;
                        height: 48px;
                        border: 1px solid rgba(0, 0, 0, 0.12);
                        border-radius: 3px;
                        cursor: pointer;
                        background: #F5F5F5;
                    }

                    input:checked + span, input:checked + img {
                        border: 1px solid #000000;
                        background: #000000;
                        color: #fff;
                    }

                    input {
                        display: none;
                    }
                }

                .no-options {
                    color: #ff6472;
                }
            }

            &.color {
                .swatch-container {
                    .swatch {
                        min-width: 48px;
                        height: 48px;

                        span {
                            min-width: 46px;
                            height: 46px;
                            border: 1px solid rgba(0, 0, 0, 0.12);
                        }
                    }
                }
            }
        }
    }
</style>