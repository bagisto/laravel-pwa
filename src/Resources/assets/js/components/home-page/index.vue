<template>
    <div class="content">

        <carousel
            :per-page="1"
            :loop="true"
            :autoplay="true"
            pagination-color="#E8E8E8"
            pagination-active-color="#979797"
        >
            <slide
                :key='slider.id'
                v-for="slider in sliders"
            >
                <img
                    alt="base-image-original"
                    :src="slider.image_url"
                />
            </slide>
        </carousel>

        <div class="category-container">

            <ul class="category-list">

                <category-card v-for="category in categories" :key='category.uid' :category="category"></category-card>

            </ul>

        </div>

        <template v-if="product.categories.length">
            <div
                class="products panel"
                :key='category.category_details.id'
                v-for="category in product.categories">

                <template v-if="category.products.length">
                    <router-link :to="'/categories/' + category.category_details.id" class="panel-heading">
                        {{ category.category_details.name }}
                    </router-link>

                    <div class="panel-content product-list product-grid-2">

                        <product-card v-for="product in category.products" :key='product.uid' :product="product"></product-card>

                    </div>
                </template>
            </div>
        </template>

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
    import { Carousel, Slide }  from 'vue-carousel';
    import ProductCard          from '../products/card';
    import CategoryCard         from '../categories/card';
    import FooterNav            from '../layouts/footer-nav';

    export default {
        name: 'home',

        components: { CategoryCard, ProductCard, FooterNav, Carousel, Slide },

        data () {
			return {
				sliders: [],
				categories: [],

                product: {
                    'new'       : [],
                    'featured'  : [],
                    'categories': [],
                }
			}
		},

        mounted () {
            this.getSliders();

            this.getCategories();

            this.getNewFeaturedProducts();
        },

        methods: {
            getSliders () {
                this.$http.get("/api/config", {
                    params: {
                        _config: 'pwa.settings.general.enable_slider'
                    }
                }).then(response => {
                    EventBus.$emit('hide-ajax-loader');

                    if (response.data.data['pwa.settings.general.enable_slider'] == "1") {
                        this.$http.get("/api/sliders")
                        .then(response => {
                            this.sliders = response.data.data;
                        })
                        .catch(function (error) {});
                    }
                })
                .catch(function (error) {});
            },

            getNewFeaturedProducts () {
                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/config", {
                    params: {
                        _config: 'pwa.settings.general.enable_new,pwa.settings.general.enable_featured'
                    }
                }).then(response => {
                    EventBus.$emit('hide-ajax-loader');

                    if (response.data.data['pwa.settings.general.enable_new'] == "1") {
                        this.getProducts('new', { 'new': 1 });
                    }

                    if (response.data.data['pwa.settings.general.enable_featured'] == "1") {
                        this.getProducts('featured', { 'featured': 1 });
                    }
                })
                .catch(function (error) {});
            },

            getCategories () {
                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/pwa/categories", { params: { parent_id: window.channel.root_category_id } })
                    .then(response => {
                        EventBus.$emit('hide-ajax-loader');

                        this.categories = response.data.data;

                        this.categories.forEach(category => {
                            if (category.show_products) {
                                // push data here to keep category according to order
                                this.getProducts(category, { 'category_id': category.id });
                            }
                        });
                    })
                    .catch(function (error) {});
            },

            getProducts (details, params) {
                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/products", { params: params })
                    .then(response => {
                        EventBus.$emit('hide-ajax-loader');

                        if (params.category_id) {
                            // pushing here will depend on ajax time
                            this.product.categories.push({
                                'category_details'  : details,
                                'products'          : response.data.data,
                            });
                        } else {
                            this.product[details] = response.data.data;
                        }

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