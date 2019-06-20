<template>
    <router-link class="review-card" :to="'/customer/account/reviews/' + review.id">
        <div class="product-image">
            <img alt="product-base-small-image" :src="review.product.base_image.small_image_url"/>
        </div>

        <div class="review-info">
            <div class="product-name">{{ review.product.name }}</div>

            <div class="ratings">
                <i v-for="i in [1, 2, 3, 4, 5]" :class="['icon', review.rating >= i ? 'star-active-icon' : 'star-icon']"></i>

                <span>{{ $t('number Stars', {number: parseInt(review.rating)}) }}</span>
            </div>
        </div>


        <i class="icon sharp-arrow-right-icon"></i>
    </router-link>
</template>

<script>
    export default {
        name: 'review-item',

        props: ['review']
    }
</script>

<style scoped lang="scss">
    .review-card {
        position: relative;
        background: #ffffff;
        margin-bottom: 28px;
        padding: 16px 48px 16px 16px;
        border-top: 1px solid rgba(0, 0, 0 ,0.12);
        border-bottom: 1px solid rgba(0, 0, 0 ,0.12);
        font-size: 14px;
        color: rgba(0, 0, 0, 0.87);
        float: left;
        width: 100%;

        .product-image {
            margin-right: 16px;
            float: left;

            img {
                width: 56px;
                height: 56px;
            }
        }

        .review-info {
            .product-name {
                font-size: 16px;
                margin-bottom: 8px;
                color: #3F60DA;
            }

            .ratings {
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
        }

        .sharp-arrow-right-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            margin-top: -12px;
            opacity: 0.16;
        }
    }
</style>