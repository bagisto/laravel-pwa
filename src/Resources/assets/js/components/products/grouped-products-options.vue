<template>
    <div class="grouped-products-container">
        <div v-for='(product, index) in groupedProducts' :key="index" class="grouped-product">
            <template v-if="product.isSaleable">
                <div class="product-details">
                    <label>{{ product.name }}</label>
                    <label class="product-price" v-html="product.formated_price"></label>
                </div>

                <div class="quantity-container" v-if="product.show_quantity_changer">
                    <label>{{ $t('Quantity') }}</label>

                    <div class="quantity">
                        <button type="button" class="btn btn-black decrease-qty"
                            @click="changeQuantity('decrease', product.id)">
                            <i class="icon minus-icon"></i>
                        </button>

                        <div class="quantity-label">
                            <span :id="`qty-${product.id}`">{{ formData.qty[product.id] }}</span>
                            {{ $t('number Units', {number: null}) }}
                        </div>

                        <button type="button" class="btn btn-black increase-qty" @click="changeQuantity('increase', product.id)">
                            <i class="icon plus-icon"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'grouped-products-options',

        props: ['product', 'formData'],

        data: function () {
            return {
                config: {},
                
                localFormData: this.formData,

                groupedProducts: this.product.grouped_products,

                selectedProductId: '',

                simpleProduct: null,

                galleryImages: []
            }
        },

        inject: ['$validator'],

        watch: {
            'localFormData.qty': {
                handler: function (val) {
                    console.log('updated');
                    console.log(val);
                    this.initGraph(this.graphId)
                },

                deep: true
            },
        },

        methods: {
            changeQuantity: function (type, productId) {
                if (type == 'increase') {
                    this.$set(this.localFormData.qty, productId, this.localFormData.qty[productId] + 1);
                } else if (type == 'decrease') {
                    if (this.localFormData.qty[productId] > 1) {
                        this.$set(this.localFormData.qty, productId, this.localFormData.qty[productId] - 1);
                    }
                }

                document.getElementById(`qty-${productId}`).innerHTML = this.localFormData.qty[productId];
            }
            
        }
    }
</script>