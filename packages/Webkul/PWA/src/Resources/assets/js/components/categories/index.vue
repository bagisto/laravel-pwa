<template>
    <div class="content" v-if="category">
        <layered-navigation
            v-show="category.display_mode == 'products_only' || category.display_mode == 'products_and_description'"
            @onFilterApplied="filterProducts($event)">
        </layered-navigation>

        <div class="category-banner" v-if="category.image_url">
            <img alt="category-image" :src="category.image_url"/>
        </div>

        <div
            v-if="category.display_mode == 'description_only' || category.display_mode == 'products_and_description'"
            class="category-description panel">
                <div class="panel-content" v-html="category.description">
                </div>
        </div>

        <div v-if="category.display_mode == 'products_only' || category.display_mode == 'products_and_description'">
            <div class="panel" v-if="products.length">
                <div class="panel-content">
                    <div class="product-list product-grid-2">

                        <product-card v-for="product in products" :key='product.uid' :product="product"></product-card>

                    </div>

                    <pagination :label="$t('Load More Products')" v-bind="productPagination" @onPaginate="paginate($event)"></pagination>
                </div>
            </div>

            <no-product-found v-else></no-product-found>
        </div>

        <div class="panel" v-if="childCategories.length">
            <div class="panel-heading">
                {{ $t('Explore') }} {{ category.name }}
            </div>

            <div class="panel-content">
                <ul class="category-list">
                    <li v-for="category in childCategories">
                        <router-link :to="'/categories/' + category.id">
                            {{ category.name }}

                            <i class="icon sharp-arrow-right-icon"></i>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>

        <div v-if="category.display_mode == 'description_only'" class="panel" style="margin-bottom: 0">
            <div class="panel-content">
                <footer-nav></footer-nav>
            </div>
        </div>
    </div>
</template>

<script>
    import FooterNav         from '../layouts/footer-nav';
    import LayeredNavigation from './layered-navigation';
    import ProductCard       from '../products/card';
    import Pagination        from '../shared/pagination';
    import NoProductFound    from './no-product-found';

    export default {
        name: 'category',

        components: { LayeredNavigation, FooterNav, ProductCard, Pagination, NoProductFound },

        data () {
			return {
				category: null,

				childCategories: [],

                products: [],

                productPagination: {},

				params: {
                    'category_id': this.$route.params.id,
                    'limit': 8
                },
			}
		},

        watch: {
            '$route.params.id': function (id) {
                this.params = { 'category_id': id };

                this.getCategory(id);
            }
        },

        mounted () {
            this.getCategory(this.$route.params.id);
        },

        methods: {
            getCategory (categoryId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/categories/' + categoryId)
                    .then(function(response) {
                        this_this.category = response.data.data;

                        this_this.childCategories = [];

                        if (this_this.category.display_mode == 'description_only') {
                            this_this.getChildCategories(categoryId);
                        }

                        this_this.products = [];

                        this_this.getProducts();

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            },

            getChildCategories (categoryId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/descendant-categories', { params: { parent_id: categoryId } })
                    .then(function(response) {
                        this_this.childCategories = response.data.data;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            },

            getProducts () {
                if (this.category.display_mode == 'description_only')
                    return;

                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/products", { params: this.params })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        response.data.data.forEach(function(product) {
                            this_this.products.push(product);
                        });

                        this_this.productPagination = response.data.meta;
                    })
                    .catch(function (error) {});
            },

            filterProducts (filters) {
                this.products = [];

                delete this.params['page'];

                for(var key in filters) {
                    if (key == 'sort')  {
                        if (filters[key].length) {
                            var sort = '';

                            if (Array.isArray(filters[key])) {
                                sort = filters[key][0];
                            } else {
                                sort = filters[key];
                            }

                            if (sort) {
                                this.params['sort'] = sort.substring(0, sort.lastIndexOf("_") + 0);
                                this.params['order'] = sort.substring(sort.lastIndexOf("_") + 1, sort.length);
                            }
                        }
                    } else {
                        if (filters[key].length) {
                            if (key == 'price') {
                                if (filters[key][1]) {
                                    this.params[key] = filters[key].join(',');
                                }
                            } else {
                                this.params[key] = filters[key].join(',');
                            }
                        } else {
                            delete this.params[key];
                        }
                    }
                }

                this.getProducts();
            },

            paginate (page) {
                this.params['page'] = page;

                this.getProducts();
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .category-banner {
        margin-bottom : 28px;

        img {
            width: 100%;
        }
    }

    .category-list {
        li {
            margin-bottom: 8px;

            &:last-child {
                margin-bottom: 0;
            }

            a {
                background: #F5F5F5;
                font-size: 16px;
                color: #000000;
                padding: 15px;
                display: block;
                text-transform: uppercase;

                .icon {
                    float: right;
                }
            }
        }
    }
</style>