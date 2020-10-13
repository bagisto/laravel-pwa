<template>
    <div class="content">
        <div class="panel" v-if="products.length">
            <div class="panel-content">
                <div class="product-list product-grid-2">

                    <product-card v-for="product in products" :key='product.uid' :product="product"></product-card>

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

        mounted () {
            this.getProducts();
        },

        methods: {
            getProducts () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/products", { params: this.params })
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
            }
        }
    }
</script>

<style scoped lang="scss">
    
</style>