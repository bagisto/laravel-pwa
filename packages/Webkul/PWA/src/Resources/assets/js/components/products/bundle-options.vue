<template>

</template>
<script>
    export default {
        name: 'bundle-options',

        props: ['product', 'formData'],

        data:() => ({
            config: {},

            options: [],

            simpleProduct: null,

            galleryImages: []
        }),

        inject: ['$validator'],

        mounted () {
            this.getbundleConfig(this.product.id);
        },

        methods: {
            getbundleConfig (productId) {
                console.log('aayush');
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/product-bundle-config/' + productId)
                .then(function(response) {

                   this_this.config = response.data.data;

                    this_this.prepareData();

                    EventBus.$emit('hide-ajax-loader');
                })
                .catch(function (error) {});
            }
        }
    }
</script>