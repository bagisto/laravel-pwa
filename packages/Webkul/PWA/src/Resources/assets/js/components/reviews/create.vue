<template>
    <div class="content">
        <custom-header :title="$t('Review Details')"></custom-header>

        <form action="POST" @submit.prevent="validateBeforeSubmit">
            <div class="form-container" v-if="product">
                <div class="product-details">
                    <div class="product-image">
                        <img alt="product-base-small-image" :src="product.base_image.small_image_url"/>
                    </div>

                    <router-link class="product-name" :to="'/products/' + product.id">
                        {{ product.name }}
                    </router-link>
                </div>

                <div class="review-form">
                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <div class="ratings">
                            <i class="icon star-icon" v-for="i in [1, 2, 3, 4, 5]" :class="{'star-active-icon': i <= review.rating}" @click="review.rating = i"></i>

                            <input type="hidden" v-model="review.rating"/>
                        </div>
                    </div>

                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <input type="text" name="name" class="control" v-model="review.name" v-validate="'required'" :placeholder="$t('Name')" :data-vv-as="$t('Name')"/>
                        <label>{{ $t('Name') }}</label>
                        <span class="control-error" v-if="errors.has('name')">{{ errors.first('name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                        <input type="text" name="title" class="control" v-model="review.title" v-validate="'required'" :placeholder="$t('Title')" :data-vv-as="$t('Title')"/>
                        <label>{{ $t('Title') }}</label>
                        <span class="control-error" v-if="errors.has('title')">{{ errors.first('title') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('comment') ? 'has-error' : '']">
                        <textarea name="comment" class="control" v-model="review.comment" v-validate="'required'" :placeholder="$t('Review')" :data-vv-as="$t('Review')"></textarea>
                        <label>{{ $t('Review') }}</label>
                        <span class="control-error" v-if="errors.has('comment')">{{ errors.first('comment') }}</span>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-black btn-lg" :disabled="loading">{{ $t('Save') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import CustomHeader from '../layouts/custom-header';

    export default {
        name: 'customer-review-detail',

        data () {
			return {
				product: null,

				review: {
                    rating: 1
                },

                loading: false
            }
        },

        components: { CustomHeader },

        mounted () {
            this.getProduct(this.$route.params.id)
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

            validateBeforeSubmit () {
                this.loading = true;

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.saveReview()
                    } else {
                        this.loading = false;
                    }
                });
            },

            saveReview () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.post('/api/reviews/' + this.$route.params.id + '/create', this.review)
                    .then(function(response) {
                        this_this.loading = false;

                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        this_this.$router.go(-1)
                    })
                    .catch(function (error) {
                        var errors = error.response.data.errors;

                        for (name in errors) {
                            if (errors.hasOwnProperty(name)) {
                                this_this.errors.add(name, errors[name][0])
                            }
                        }

                        this_this.loading = false;
                    })
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

        .form-container {
            margin-top: 55px;

            .product-details {
                padding: 16px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                background: #ffffff;
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

            .review-form {
                padding: 16px;

                .control-group {
                    .ratings {
                        .icon {
                            width: 36px;
                            height: 36px;
                        }
                    }
                }
            }
        }
    }
</style>