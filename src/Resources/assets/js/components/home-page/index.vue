<template>
    <div class="content">

        <div class="category-container">

            <ul class="category-list">

                <category-card v-for="category in categories" :key='category.uid' :category="category"></category-card>

            </ul>

        </div>

        <div class="products panel" v-if="product.new.length">

            <div class="panel-heading">
                {{ $t('New Products') }}
            </div>

            <div class="panel-content product-list product-grid-2">

                <product-card v-for="product in product.new" :key='product.uid' :product="product"></product-card>

            </div>

        </div>

        <div class="products panel" v-if="product.featured.length">

            <div class="panel-heading">
                {{ $t('Featured Products') }}
            </div>

            <div class="panel-content product-list product-grid-2">

                <product-card v-for="product in product.featured" :key='product.uid' :product="product"></product-card>

            </div>

        </div>

         <div class="panel" style="margin-bottom: 0">
            <div class="panel-content">
                <footer-nav></footer-nav>
            </div>
         </div>

    </div>
</template>

<script>
    import CategoryCard from '../categories/card';
    import ProductCard  from '../products/card';
    import FooterNav  from '../layouts/footer-nav';

    export default {
        name: 'home',

        components: { CategoryCard, ProductCard, FooterNav },

        data () {
			return {
				categories: [],

                product: {
                    'new': [],
                    'featured': []
                }
			}
		},

        mounted () {
            this.getCategories();

            this.getProducts('new', { 'new': 1, limit: 4 });

            this.getProducts('featured', { 'featured': 1, limit: 4 });
        },

        methods: {
            getCategories () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/descendant-categories", { params: { parent_id: window.channel.root_category_id } })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.categories = response.data.data;
                    })
                    .catch(function (error) {});
            },

            getProducts (type, params) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/products", { params: params })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.product[type] = response.data.data;
                    })
                    .catch(function (error) {});
            }
        }
    }
</script>

<style lang="scss">
    @import '~@/_variables.scss';

    .products {
        .list {
            padding: 16px;
        }
    }
</style>