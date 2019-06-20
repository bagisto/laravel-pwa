<template>
    <div class="cart-item">
        <div class="cart-item-content">
            <div class="product-image">
                <img alt="product-base" :src="cartItem.product.base_image.small_image_url"/>
            </div>

            <div class="cart-item-info">
                <div class="product-name">{{ cartItem.product.name }}</div>

                <div class="cart-item-options">
                    <div class="attributes" v-if="cartItem.additional.attributes">
                        <div class="option" v-for="attribute in cartItem.additional.attributes">
                            <label>{{ attribute.attribute_name }} : </label>
                            <span>{{ attribute.option_label }}</span>
                        </div>
                    </div>

                    <div class="cart-item-price">
                        <label>{{ $t('Price :') }} </label>
                        <span>{{ cartItem.formated_price }}</span>
                    </div>

                    <div class="cart-item-subtotal">
                        <label>{{ $t('Subtotal :') }} </label>
                        <span>{{ cartItem.formated_total }}</span>
                    </div>
                </div>
            </div>

            <div class="cart-item-qty">
                <div class="quantity">
                    <button type="button" class="btn btn-black decrease-qty"
                        @click="quantities[cartItem.id] > 1 ? quantities[cartItem.id]-- : quantities[cartItem.id]">
                        <i class="icon minus-icon"></i>
                    </button>

                    <div class="quantity-label">
                        {{ quantities[cartItem.id] }}
                    </div>

                    <button type="button" class="btn btn-black increase-qty" @click="quantities[cartItem.id]++">
                        <i class="icon plus-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="cart-item-actions">
            <div class="wishlist-link" @click="moveToWishlist">
                <i class="icon wishlist-icon"></i>

                <span>{{ $t('Move To Wishlist') }}</span>
            </div>

            <div class="remove-link" @click="removeItem">
                <i class="icon sharp-trash-icon"></i>

                <span>{{ $t('Remove Item') }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'cart-item',

        props: ['cartItem', 'quantities'],

        created () {
            this.$set(this.quantities, this.cartItem.id, this.cartItem.quantity)
        },

        methods: {
            moveToWishlist () {
                this.$emit('moveToWishlist')
            },

            removeItem () {
                this.$emit('removeItem')
            }
        }
    }
</script>

<style scoped lang="scss">
    .cart-item {
        background: #ffffff;
        margin-bottom: 12px;
        border-bottom: 1px solid rgba(0, 0, 0 ,0.12);
        position: relative;

        &:last-child {
            margin-bottom: 0;
        }

        .cart-item-content {
            padding: 16px;
            width: 100%;
            min-height: 165px;
            display: inline-block;

            .product-image {
                margin-right: 16px;
                float: left;

                img {
                    width: 96px;
                    height: 96px;
                }
            }

            .cart-item-info {
                overflow: hidden;

                .product-name {
                    margin-bottom: 16px;
                    font-size: 14px;
                    color: rgba(0, 0, 0, 0.87);
                }

                .cart-item-options {
                    .attributes {
                        display: inline-block;

                        .option {
                            display: inline-block;

                            span {
                                margin-right: 8px;
                            }
                        }
                    }

                    > div {
                        margin-bottom: 8px;
                        font-size: 14px;

                        &:last-child {
                            margin-bottom: 0;
                        }

                        label {
                            color: rgba(0, 0, 0, 0.56);
                            font-weight: 600;
                        }

                        span {
                            color: #000000;
                            font-weight: 600;
                        }
                    }
                }
            }

            .cart-item-qty {
                position: absolute;
                top: 127px;
                width: 96px;

                label {
                    display: block;
                    font-weight: 700;
                    font-size: 12px;
                    color: rgba(0, 0, 0, 0.56);
                    margin-bottom: 16px;
                }

                .quantity {
                    position: relative;

                    .btn {
                        padding: 0px 5px;

                        .icon {
                            width: 12px;
                            height: 12px;
                        }

                        &.decrease-qty {
                            float: left;
                        }

                        &.increase-qty {
                            float: right;
                        }
                    }

                    .quantity-label {
                        position: absolute;
                        left: 0;
                        right: 0;
                        top: 2px;
                        color: rgba(0, 0, 0, 0.87);
                        opacity: 0.87;
                        font-size: 16px;
                        text-align: center;
                        pointer-events: none;
                    }
                }
            }
        }

        .cart-item-actions {
            border-top: 1px solid rgba(0, 0, 0, 0.12);
            font-weight: 600;
            font-size: 14px;

            .wishlist-link {
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

            .remove-link {
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
    }
</style>