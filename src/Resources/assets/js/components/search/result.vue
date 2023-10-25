<template>
    <div class="content">
        <div class="panel" v-if="products.length">
            <div class="panel-content">
                <div class="product-list product-grid-2">

                    <product-card 
                    v-for="product in products"  
                        :is-customer="customer ? true : false" 
                        :key='product.uid' 
                        :product="product"
                        :isWishlisted="product.is_wishlisted"
                        @updateWishlistStatus="updateWishlistStatus" 
                    ></product-card>

                </div>

                <pagination :label="$t('Load More Products')" v-bind="pagination" @onPaginate="paginate($event)"></pagination>
            </div>
        </div>

        <empty-search v-else></empty-search>
    </div>
</template>

<script>
    import ProductCard from '../products/card';
    import Pagination  from '../shared/pagination';
    import EmptySearch from './empty-search';
    import {
        mapState,
        mapActions
    } from 'vuex';

    export default {
        name: 'search-result',

        components: { ProductCard, Pagination, EmptySearch },

        data () {
			return {
                products: [],

                pagination: {},

                params: {
                    'search': this.$route.params.term
                }
			}
		},

        computed: mapState({
            customer: state => state.customer,
        }),

        mounted () {
            this.getProducts();

            this.getCustomer();
        },

        methods: {
            ...mapActions([
                'getCustomer',
            ]),

            getProducts () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/pwa/products", { params: this.params })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        response.data.data.forEach(function(product) {
                            this_this.products.push(product);
                        });

                        this_this.pagination = response.data.meta;
                    })
                    .catch(function (error) {});
            },

            paginate (page) {
                this.params['page'] = page;

                this.getProducts();
            },

            updateWishlistStatus (productId, isWishlisted) {
                const newProductIndex = this.product.new.findIndex(item => item.id === productId);

                if (newProductIndex > -1) {
                    this.product.new[newProductIndex].is_wishlisted = isWishlisted;
                }

                // Find the product in the 'featured' section and update its wishlisted status
                const featuredProductIndex = this.product.featured.findIndex(item => item.id === productId);
                
                if (featuredProductIndex > -1) {
                    this.product.featured[featuredProductIndex].is_wishlisted = isWishlisted;
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    
</style>