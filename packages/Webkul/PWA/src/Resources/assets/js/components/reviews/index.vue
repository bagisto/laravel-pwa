<template>
    <div class="product-reviews" v-if="reviews.length">
        <div class="product-details">
            <div class="product-image">
                <img alt="product-small-image" :src="product.base_image.small_image_url"/>
            </div>

            <router-link class="product-name" :to="'/products/' + product.id">
                {{ product.name }}
            </router-link>
        </div>

        <div class="review-summary">
            <div class="donut-chart">
                <donut-chart :data="ratingPercentage">
                    <ul>
                        <li v-for="i in [1, 2, 3, 4, 5]">
                            <i :class="['icon', 'star-' + i + '-icon']"></i>

                            {{ i }}
                        </li>
                    </ul>
                </donut-chart>
            </div>

            <div class="review">
                <span class="rating">
                    {{ product.reviews.average_rating }}
                    <i class="icon star-white-icon"></i>
                </span>

                <span class="based-on">
                    {{ $t('Based On') }}
                </span>

                <span class="total-review">
                    {{ $t('number Reviews', {number: product.reviews.total}) }}
                </span>

                <router-link :to="'/reviews/' + product.id + '/create'">
                    {{ $t('Add Your Review') }}
                </router-link>
            </div>
        </div>

        <div class="panel" v-if="reviews.length">
            <div class="review-list">
                <div class="review-item" v-for="review in reviews">
                    <div class="title">
                        <span class="rating" :class="['star-' + parseInt(review.rating)]">
                            {{ review.rating }}
                            <i class="icon star-white-icon"></i>
                        </span>

                        {{ review.title }}
                    </div>

                    <div class="comment">
                        {{ review.comment }}
                    </div>

                    <div class="info">
                        <div class="review-by">
                            <span class="by">{{ $t('Review by -') }} </span>

                            <span class="name">{{ review.name }}</span>
                        </div>

                        <div class="date">
                            {{ new Date(review.created_at.date) | moment("D MMMM YYYY") }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="pagination-container" v-if="pagination.current_page < pagination.last_page">
                <pagination :label="$t('Load More Reviews')" v-bind="pagination" @onPaginate="paginate($event)"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
    import DonutChart from '../shared/donut-chart';
    import Pagination from '../shared/pagination';

    export default {
        name: 'reviews',

        components: { DonutChart, Pagination },

        data () {
			return {
                product: [],

                reviews: [],

                pagination: {},

				page: 0
            }
        },

        mounted () {
            this.getProduct(this.$route.params.id);
        },

        computed: {
            ratingPercentage () {
                var ratings = JSON.parse(this.product.reviews.percentage);

                var ratingFinal = [];

                for (var key in ratings) {
                    ratingFinal.push(ratings[key])
                }

                return ratingFinal;
            }
        },

        methods: {
            getProduct (productId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/products/' + productId)
                    .then(function(response) {
                        this_this.product = response.data.data;

                        EventBus.$emit('hide-ajax-loader');

                        this_this.getReviews(productId);
                    })
                    .catch(function (error) {});
            },

            getReviews (productId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/reviews', { params: { product_id: productId, page: this.page, status: 'approved' } })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        response.data.data.forEach(function(review) {
                            this_this.reviews.push(review);
                        });

                        this_this.pagination = response.data.meta;
                    })
                    .catch(function (error) {});
            },

            paginate (page) {
                this.page = page;

                this.getReviews(this.$route.params.id);
            }
        }
    }
</script>

<style scoped lang="scss">
    ul {
        text-align: center;

        li {
            display: inline-block;
            margin-right: 12px;
            margin-bottom: 12px;
        }
    }

    .product-details {
        padding: 16px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        background: #ffffff;
        margin-bottom: 12px;
        display: flex;
        align-items:center;

        .product-image {
            margin-right: 16px;
            float: left;

            img {
                width: 96px;
                height: 96px;
            }
        }

        .product-name {
            font-size: 14px;
            color: #000000;
            margin-bottom: 5px;
        }
    }

    .review-summary {
        padding: 16px;
        background: #ffffff;

        .donut-chart {
            width: 50%;
            float: left;
            position: relative;
            border-right: 1px solid rgba(0, 0, 0, 0.12);
        }

        .review {
            width: 50%;
            padding: 16px 0px 16px 16px;
            display: inline-block;

            .rating {
                background: #000000;
                padding: 5px 8px;
                color: #ffffff;
                font-weight: 600;
                margin-right: 8px;
                margin-bottom: 8px;
                display: inline-block;

                .icon {
                    vertical-align: middle;
                    margin-bottom: 2px;
                }
            }

            .based-on {
                font-weight: 600;
                font-size: 14px;
                color: rgba(0, 0, 0, 0.54);
                display: block;
                margin-bottom: 8px;
            }

            .total-review {
                font-weight: 600;
                font-size: 20px;
                color: rgba(0, 0, 0, 0.87);
                margin-right: 16px;
                display: block;
                margin-bottom: 16px;
            }

            a {
                font-weight: 600;
                font-size: 14px;
                text-transform: uppercase;
                vertical-align: middle;
            }
        }
    }

    .review-list {
        background: #ffffff;
        border-top: 1px solid rgba(0, 0, 0, 0.12);

        .review-item {
            padding: 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.12);

            &:last-child {
                border-bottom: 0;
            }

            .title {
                font-weight: 600;
                font-size: 14px;
                margin-bottom: 8px;
                color: #000000;

                .rating {
                    padding: 5px 8px;
                    color: #ffffff;
                    font-weight: 600;
                    margin-right: 8px;
                    display: inline-block;

                    &.star-1 {
                        background: #E51A1A;
                    }

                    &.star-2 {
                        background: #FF4848;
                    }

                    &.star-3 {
                        background: #FFA100;
                    }

                    &.star-4 {
                        background: #FFCC00;
                    }

                    &.star-5 {
                        background: #6BC700;
                    }

                    .icon {
                        vertical-align: middle;
                        margin-bottom: 2px;
                    }
                }
            }

            .comment {
                margin-bottom: 8px;
            }

            .info {
                display: inline-block;
                width: 100%;
                margin-top: 8px;

                .review-by {
                    float: left;

                    .by {
                        font-size: 12px;
                        color: rgba(0, 0, 0, 0.56);
                    }

                    .name {
                        font-weight: 600;
                        color: #000000;
                    }
                }

                .date {
                    float: right;
                    color: rgba(0, 0, 0, 0.56);
                }
            }
        }
    }

    .pagination-container {
        padding: 16px;
    }
</style>