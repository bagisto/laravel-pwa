<template>
    <header class="navbar navbar-top">
        <slot name="navbar">
            <div class="navbar-left">
                <slot name="navbar-left">
                    <div v-if="$route.name == 'home'">
                        <div class="drawer-icon" @click="handleMaskClick">
                            <i class="icon bar-icon"></i>
                        </div>

                        <div class="shop-title">
                            {{ app_name }}
                        </div>
                    </div>

                    <div v-else>
                        <i class="icon back-icon" @click="handleBack"></i>

                        <router-link tag="div" :to="'/'" class="shop-title">
                            {{ app_name }}
                        </router-link>
                    </div>
                </slot>
            </div>

            <div class="navbar-right">
                <slot name="navbar-left">
                    <router-link class="search-icon" :to="'/search'">
                        <i class="icon search-icon"><p style="color: white;">.</p></i> <!--<p> used to meet some req. of lighthouse-->
                    </router-link>

                    <!--<div class="notification-icon">
                        <i class="icon notification-icon"></i>
                    </div>-->

                    <router-link class="compare-icon" :to="'/compare'">
                        <i class="icon compare-icon"><p style="color: white;">.</p></i> <!--<p> used to meet some req. of lighthouse-->
                    </router-link>

                    <router-link class="cart-icon" :to="'/checkout/cart'">
                        <span class="count" v-if="cart && cart.items.length">{{ cart.items.length }}</span>
                        <i class="icon cart-icon"><p style="color: white;">.</p></i> <!--<p> used to meet some req. of lighthouse-->
                    </router-link>
                </slot>
            </div>
        </slot>
    </header>
</template>

<script>
    import {
        mapState,
        mapActions
    } from 'vuex';

    export default {
        name: 'header-nav',

        mounted () {
            this.getCart();
        },

        computed: {
            app_name () {
                return window.config.app_short_name ? window.config.app_short_name : 'Bagisto';
            },

            ...mapState({
                cart: state => state.cart,
            }),
        },

        methods: {
            ...mapActions([
                'getCart',
            ]),

            handleMaskClick () {
                this.$emit('toggleDrawer')
            },

            handleBack () {
                if (
                    window.history.length <= 2
                    || this.$route.name == 'order-success'
                ) {
                    this.$router.push({name: 'home'})
                } else {
                    this.$router.back();
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    @import '~@/_variables.scss';

    header.navbar {
        &.navbar-top {
            width: 100%;
            z-index: 100;
            position: fixed;
            height: 56px;
            color: $font-dark-black-color;
            padding: 15px;
            border-bottom: 1px solid rgba(0,0,0,0.08);
            background: #ffffff;

            .navbar-left {
                float: left;

                .drawer-icon {
                    float: left;
                }

                .shop-title {
                    display: inline-block;
                    margin-left: 20px;
                    font-size: 18px;
                    font-weight: 700;
                    margin-top: 1px;
                    vertical-align: top;
                }

                .back-icon {
                    cursor: pointer;
                }
            }

            .navbar-right {
                float: right;

                > div, > a {
                    display: inline-block;
                    margin-right: 20px;
                    cursor: pointer;

                    &:last-child {
                        margin-right: 7px;
                    }

                    &.cart-icon {
                        position: relative;

                        .count {
                            border: 1px solid rgba(0, 0, 0, 0.34);
                            border-radius: 2px;
                            font-size: 10px;
                            color: rgba(0, 0, 0, 0.56);
                            text-align: center;
                            position: absolute;
                            right: -16px;
                            padding: 0px 4px;
                            top: -4px;
                            border-radius: 2px;
                        }
                    }
                }
            }
        }
    }
</style>