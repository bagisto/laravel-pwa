<template>
    <div class="content">
        <custom-header :title="$t('Review Details')"></custom-header>

        <div class="review-details" v-if="review">
            <div class="product-details">
                <div class="product-image">
                    <img alt="review-base-small-image" :src="review.product.base_image.small_image_url"/>
                </div>

                <div class="product-info">
                    <router-link class="product-name" :to="'/products/' + review.product.id">
                        {{ review.product.name }}
                    </router-link>

                    <div class="product-ratings">
                        <i
                            v-for="i in [1, 2, 3, 4, 5]"
                            :class="['icon', review.product.reviews.average_rating >= i ? 'star-active-icon' : 'star-icon']"
                        ></i>

                        <span>{{ $t('number Stars', {number: parseInt(review.product.reviews.average_rating)}) }}</span>
                    </div>

                    <router-link class="product-total-reviews" :to="'/reviews/' + review.product.id">
                        {{ $t('number Reviews', {number: review.product.reviews.total}) }}
                    </router-link>
                </div>
            </div>

            <div class="review-heading">
                <h3>{{ $t('Your Review') }}</h3>
            </div>

            <div class="review-info">
                <h2 class="review-title">{{ review.title }}</h2>

                <p class="review-comment">{{ review.comment }}</p>

                <div class="ratings">
                    <label>{{ $t('Rating') }}</label>

                    <span class="stars">
                        <i
                            v-for="i in [1, 2, 3, 4, 5]"
                            :class="['icon', review.rating >= i ? 'star-active-icon' : 'star-icon']"
                        ></i>
                    </span>

                    <span>{{ $t('number Stars', {number: parseInt(review.rating)}) }}</span>
                </div>

                <div class="review-date">
                    <label>{{ $t('Submitted on') }}</label>
                    <span>{{ new Date(review.created_at.date) | moment("MMMM D, YYYY") }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomHeader from '../../../layouts/custom-header';

    export default {
        name: 'customer-review-detail',

        data () {
			return {
				review: null,
            }
        },

        components: { CustomHeader },

        mounted () {
            this.getReview(this.$route.params.id)
        },

        methods: {
            getReview (reviewId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/reviews/' + reviewId)
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.review = response.data.data;
                    })
                    .catch(function (error) {});
            }
        }
    }
</script>

<style scoped lang="scss">
    .content {
        position: absolute;
        bottom: 0;
        top: 0;
        width: 100%;
        background: #ffffff;

        .review-details {
            margin-top: 55px;

            .product-details {
                padding: 16px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.12);

                .product-image {
                    margin-right: 16px;
                    float: left;

                    img {
                        width: 96px;
                        height: 96px;
                    }
                }

                .product-info {
                    .product-name {
                        font-size: 14px;
                        color: #000000;
                        margin-bottom: 5px;
                        display: block;
                    }

                    .product-ratings {
                        margin-bottom: 7px;

                        .icon {
                            width: 22px;
                            height: 22px;
                        }

                        span {
                            font-size: 14px;
                            color: rgba(0, 0, 0, 0.56);
                            vertical-align: top;
                            display: inline-block;
                            margin-top: 3px;
                            margin-left: 10px;
                        }
                    }

                    .product-total-reviews {
                        font-size: 14px;
                    }
                }
            }

            .review-heading {
                padding: 16px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.12);

                h3 {
                    font-weight: 700;
                    font-size: 12px;
                    color: rgba(0, 0, 0, 0.56);
                    text-transform: uppercase;
                }
            }

            .review-info {
                padding: 16px;

                .review-title {
                    font-size: 16px;
                    color: #000000;
                    margin-top: 3px;
                    margin-bottom: 8px;
                }

                .review-comment {
                    font-size: 13px;
                    color: rgba(0, 0, 0, 0.56);
                    margin-bottom: 14px;
                }

                .ratings {
                    margin-bottom: 18px;

                    label {
                        font-size: 14px;
                        color: #000000;
                        vertical-align: middle;
                    }

                    .stars {
                        vertical-align: middle;

                        .icon {
                            width: 22px;
                            height: 22px;
                        }
                    }

                    span {
                        vertical-align: middle;
                        font-size: 14px;
                        color: rgba(0, 0, 0, 0.56);
                        display: inline-block;
                        margin-left: 10px;
                    }
                }

                .review-date {
                    label {
                        font-size: 14px;
                        display: block;
                        color: #000000;
                        margin-bottom: 3px;
                    }

                    span {
                        font-size: 13px;
                        color: rgba(0, 0, 0, 0.56);
                    }
                }
            }
        }
    }
</style>