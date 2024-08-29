<template>
    <div class="content">
        <carousel
                :per-page="1"
                :loop="true"
                :autoplay="true"
                :autoplayTimeout="5000"
                pagination-color="#E8E8E8"
                pagination-active-color="#979797"
            >
            <slide
                :key='slider.id'
                v-for="slider in sliders"
            >
                <a
                    :href="slider.link"
                    class="slider-image-container"
                    v-if="slider.link && slider.link != ''"
                >
                    <img
                        class="slider-image"
                        alt="base-image-original"
                        :src="slider.image"
                    />
                </a>

                <img
                    v-else
                    class="slider-image"
                    alt="base-image-original"
                    :src="slider.image"
                />

                <!-- <div class="show-content" v-html="slider.title">
                </div> -->
            </slide>
        </carousel>

        <div class="category-container" v-if="showCategories">
            <ul class="category-list">
                <category-card v-for="category in categories" :key='category.uid' :category="category"></category-card>
            </ul>
        </div>

        <div :key="index" class="products panel" v-for="(content, index) in homePageContent">
            <template v-if="content.products && content.products.length">
                <div class="category-title">
                    <span class="panel-heading">
                        {{ content.category_details.name }}
                    </span>

                    <router-link :to="'/categories/' + content.category_details.id" class="panel-heading view-all-btn">
                        {{ $t('View All Products') }}
                    </router-link>
                </div>

                <div class="panel-content product-list product-grid-2">

                    <product-card :is-customer="customer ? true : false" v-for="product in content.products" :key='product.uid' :product="product"></product-card>

                </div>
            </template>
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

                        <product-card :is-customer="customer ? true : false" v-for="product in category.products" :key='product.uid' :product="product"></product-card>

                    </div>
                </template>
            </div>
        </template>

        <div class="products panel" v-if="product.new.length">

            <div class="panel-heading">
                {{ $t('New Products') }}
            </div>

            <div class="panel-content product-list product-grid-2">

                <product-card :is-customer="customer ? true : false" v-for="product in product.new" :key='product.uid' :product="product"></product-card>

            </div>

        </div>

        <div class="products panel" v-if="product.featured.length">

            <div class="panel-heading">
                {{ $t('Featured Products') }}
            </div>

            <div class="panel-content product-list product-grid-2">

                <product-card :is-customer="customer ? true : false" v-for="product in product.featured" :key='product.uid' :product="product"></product-card>

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
    import Advertisement        from './advertisements/advertisement';
    import {
        mapState,
        mapActions
    } from 'vuex';

    export default {
        name: 'home',

        components: {
            Slide,
            Carousel,
            FooterNav,
            ProductCard,
            CategoryCard,
            Advertisement,
        },

        data: function () {
			return {
                sliders: [],
				categories: [],
				advertisements: [],
                homePageContent: {},
                showCategories: false,

                product: {
                    'new'       : [],
                    'featured'  : [],
                    'categories': [],
                }
			}
        },

        computed: mapState({
            customer: state => state.customer,
        }),

        mounted () {
            this.getConfigValues();

            this.getCategories();

            this.getHomePageContent();

            this.getCustomer();
        },

        methods: {
            ...mapActions([
                'getCustomer',
            ]),

            getSliders () {
                this.$http.get("/api/pwa/sliders")
                    .then(response => {

                        this.sliders = response.data.images;

                        this.sliders.forEach(item => {
                            if (!item.image.startsWith(window.config.app_base_url)) {
                                item.image = window.config.app_base_url + item.image;
                            }
                        });
                    })
                    .catch(function (error) {});
            },

            getConfigValues () {
                EventBus.$emit('show-ajax-loader');

                var enable_new_key = 'pwa.settings.general.enable_new';
                var enable_slider_key = 'pwa.settings.general.enable_slider';
                var enable_featured_key = 'pwa.settings.general.enable_featured';
                var enable_categories_home_page_listing_key = 'pwa.settings.general.enable_categories_home_page_listing';

                const configKeys = [
                    enable_new_key,
                    enable_slider_key,
                    enable_featured_key,
                    enable_categories_home_page_listing_key,
                ];

                this.$http.get("/api/v1/core-configs", {
                    params: {
                        _config: configKeys
                    }
                }).then(response => {

                    EventBus.$emit('hide-ajax-loader');
                    if (response.data.data[enable_new_key] == "1") {
                        this.getProducts('new', { 'new': 1, limit: 4 });
                    }

                    if (response.data.data[enable_featured_key] == "1") {
                        this.getProducts('featured', { 'featured': 1, limit: 4 });
                    }

                    if (response.data.data[enable_slider_key] == "1") {
                        this.getSliders();
                    }

                    if (response.data.data[enable_categories_home_page_listing_key] == "1") {
                        this.showCategories = true;
                    }
                })
                .catch(error =>  {
                    console.error(error);
                });
            },

            getHomePageContent () {
                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/pwa/layout")
                    .then(response => {
                        EventBus.$emit('hide-ajax-loader');

                        let homePageContent = response.data.data.home_page_content;
                        let homePageContentArray = homePageContent.replace(/<\/?[^>]+(>|$)/g, "").split(",");

                        homePageContentArray.forEach(content => {

                            let base_content = content.toLowerCase().trim();
                            this.homePageContent[base_content] = {};

                            if (this.categories) {
                                this.categories.filter(category => {

                                    if (category.slug.toLowerCase() == base_content) {

                                        this.homePageContent[base_content] = {
                                            'products' : [],
                                            'category_details' : category,
                                        }

                                        this.getProducts(base_content, { 'category_id': category.id });
                                    }
                                });
                            }
                        });
                    })
                    .catch(function (error) {});
            },

            getCategories () {
                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/v1/descendant-categories", { params: { parent_id: window.channel.root_category_id } })
                    .then(response => {
                        EventBus.$emit('hide-ajax-loader');

                        this.categories = response.data.data;

                        this.categories.forEach(category => {

                            if (this.homePageContent[category.slug]) {
                                this.homePageContent[category.slug] = {
                                    'category_details' : category,
                                }

                                this.getProducts(category.slug, { 'category_id': category.id });
                            }
                        });
                    })
                    .catch(error => {});
            },

            getProducts (details, params) {
                EventBus.$emit('show-ajax-loader');
                this.$http.get("/api/v1/products", { params: params })
                    .then(response => {

                        EventBus.$emit('hide-ajax-loader');

                        if (params.category_id) {
                            if (this.homePageContent[details]) {
                                this.$set(this.homePageContent[details], 'products', response.data.data);

                                this.$forceUpdate();
                            }
                        } else {
                            this.product[details] = response.data.data;
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
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

    .slider-image {
        width: 100%;
    }

    .view-all-btn {
        color: blue !important;
    }

    .panel-heading {
        display: inline-block;
    }

    .category-title {
        a,
        span {
            display: block;
        }

        a {
            text-align: right;
        }
    }

    .VueCarousel-slide {
        position: relative;
    }

    .slider-image-container {
        z-index: 100;
        position: relative;
    }

    .show-content {
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: table;
        text-align: center;
        position: absolute;

        p {
            display: table-cell;
            vertical-align: middle;
        }
    }
</style>
