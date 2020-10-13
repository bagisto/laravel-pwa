<template>
    <div class="order-card">
        <div class="order-details">
            <div class="order-id">#{{ order.id }}</div>
            <div class="order-status">
                <label :class="order.status">{{ order.status_label }}</label>
            </div>
            <div class="order-date">{{ new Date(order.created_at.date) | moment("D MMMM YYYY") }}</div>
            <div class="order-price">{{ order.formated_grand_total }}</div>

            <i class="icon sharp-arrow-right-icon"></i>
        </div>

        <div class="order-actions">
            <router-link class="view-link" :to="'/customer/account/orders/' + order.id">
                <i class="icon sharp-arrow-line-icon"></i>

                <span>{{ $t('Detail') }}</span>
            </router-link>

            <div class="review-link" @click="bottomSheetOpen = true">
                <i class="icon sharp-post-review-icon"></i>

                <span>{{ $t('Review') }}</span>
            </div>
        </div>

        <bottom-sheet :show="bottomSheetOpen" :class-name="'filter-bottom-sheet'" @onBottomSheetClose="bottomSheetOpen = false;">
            <div slot="header">
                {{ $t('Choose a product to review') }}
            </div>

            <div slot="content">
                <div class="product-list">

                    <router-link class="product-item" v-for="orderItem in order.items" :key="order.uid" :to="'/reviews/' + orderItem.product.id + '/create'">
                        <div class="product-image">
                            <img alt="product-base-small-image" :src="orderItem.product.base_image.small_image_url"/>
                        </div>

                        <div class="product-name">
                            {{ orderItem.product.name }}
                        </div>
                    </router-link>

                </div>
            </div>
        </bottom-sheet>
    </div>
</template>

<script>
    import BottomSheet from '../../../../shared/bottom-sheet';

    export default {
        name: 'order-card',

        components: { BottomSheet },

        props: ['order'],

        data () {
			return {
                bottomSheetOpen: false
            }
        }
    }
</script>

<style scoped lang="scss">
    .order-card {
        position: relative;
        background: #ffffff;
        margin-bottom: 28px;

        .order-details {
            padding: 16px;

            .order-id {
                font-weight: 600;
                font-size: 18px;
                color: rgba(0, 0, 0, 0.87);
                display: block;
                margin-bottom: 11px;
            }

            .order-status {
                margin-bottom: 11px;
                display: block;

                label {
                    font-weight: 600;
                    font-size: 14px;
                    color: #FFFFFF;
                    text-align: center;
                    text-transform: uppercase;
                    padding: 3px 6px;

                    &.pending {
                        background: #FFCC00;
                    }

                    &.pending_payment {
                        background: #FFCC00;
                    }

                    &.processing {
                        background: #59A600;
                    }

                    &.completed {
                        background: #59A600;
                    }

                    &.canceled {
                        background: #FF4848;
                    }

                    &.closed {
                        background: #000000;
                    }

                    &.fraud {
                        background: #FF4848;
                    }
                }
            }

            .order-date {
                font-size: 14px;
                color: rgba(0, 0, 0, 0.87);
                display: block;
                margin-bottom: 8px;
            }

            .order-price {
                font-weight: 700;
                font-size: 16px;
                color: rgba(0, 0, 0, 0.87);
                display: block;
            }

            .sharp-arrow-right-icon {
                position: absolute;
                right: 16px;
                bottom: 50%;
                margin-bottom: 12px;
                opacity: 0.16;
            }
        }

        .order-actions {
            border: 1px solid rgba(0, 0, 0, 0.12);
            font-weight: 600;
            font-size: 14px;

            .view-link {
                float: left;
                width: 50%;
                display: inline-block;
                padding: 16px;
                cursor: pointer;
                color: rgba(0, 0, 0, 0.87);

                .icon {
                    float: left;
                    margin-right: 8px;
                    opacity: 0.56;
                }

                span {
                    line-height: 24px;
                }
            }

            .review-link {
                width: 50%;
                display: inline-block;
                padding: 16px;
                cursor: pointer;
                color: rgba(0, 0, 0, 0.87);

                .icon {
                    float: left;
                    margin-right: 8px;
                    opacity: 0.56;
                }

                span {
                    line-height: 24px;
                }
            }
        }

        .product-list {
            padding: 16px;
            display: inline-block;

            .product-item {
                margin-bottom: 16px;
                display: inline-block;
                display: flex;
                align-items:center;

                &:last-child {
                    margin-bottom: 0;
                }

                .product-image {
                    margin-right: 16px;

                    img {
                        width: 96px;
                        height: 96px;
                    }
                }

                .product-name {
                    font-size: 14px;
                    color: rgba(0, 0, 0, 0.87);
                    font-weight: 500;
                }
            }
        }
    }
</style>