<template>
    <div class="content">
        <custom-header :title="$t('Reviews')"></custom-header>

        <div class="review-list" v-if="reviews.length">
            <review-card v-for="review in reviews" :key='review.id' :review="review" @onRemove="removeReview(review)"></review-card>
        </div>

        <empty-review-list v-else></empty-review-list>
    </div>
</template>

<script>
    import CustomHeader    from '../../../layouts/custom-header';
    import ReviewCard      from './card';
    import EmptyReviewList from './empty-review-list';

    export default {
        name: 'customer-review-list',

        components: { CustomHeader, ReviewCard, EmptyReviewList },

        props: ['customer'],

        data () {
            return {
                reviews: []
            }
        },

        mounted () {
            this.getReviews()
        },

        methods: {
            getReviews () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/pwa/customer/reviews', { params:
                    {
                        customer_id: this.customer.id
                    }
                }).then(function(response) {

                        EventBus.$emit('hide-ajax-loader');

                        this_this.reviews = response.data.data.data;
                    })
                    .catch(function (error) {});
            },

            removeReview (review) {
                let index = this.reviews.indexOf(review)

                this.reviews.splice(index, 1);
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

        .review-list {
            margin-top: 54px;
        }
    }
</style>
