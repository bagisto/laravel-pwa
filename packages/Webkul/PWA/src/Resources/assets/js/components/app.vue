<template>
    <div id="app-inner">

        <div slot="content">
            <header-nav @toggleDrawer="handleToggleDrawer"></header-nav>

            <drawer-sidebar ref="drawer">
                <div class="drawer">

                    <div class="drawer-header">
                        <router-link :to="'/customer/login-register'" class="login-info" v-if="! currentUser">
                            <div class="avatar"></div>
                            <h2>{{ $t('Sign In') }}</h2>
                            <p>{{ $t('to your account') }}</p>
                            <i class="icon arrow-right-icon"></i>
                        </router-link>

                        <router-link :to="'/customer/account/dashboard'" class="login-info" v-if="currentUser">
                            <div class="avatar"></div>
                            <h2>{{ $t('Hello!') }}</h2>
                            <p>{{ currentUser.name }}</p>
                            <i class="icon arrow-right-icon"></i>
                        </router-link>
                    </div>

                    <div class="drawer-content">

                        <div class="drawer-box categories">
                            <h2>{{ $t('Categories') }}</h2>

                            <ul>
                                <li v-for="category in categories">
                                    <router-link :to="'/categories/' + category.id">
                                        {{ category.name }}
                                        <i class="icon sharp-arrow-right-icon"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </div>

                        <div class="drawer-box account" v-if="currentUser">
                            <h2>{{ $t('Account') }}</h2>

                            <ul>
                                <li>
                                    <router-link :to="'/customer/account/dashboard'">
                                        {{ $t('Dashboard') }}
                                        <i class="icon sharp-arrow-right-icon"></i>
                                    </router-link>
                                </li>

                                <li>
                                    <router-link :to="'/customer/account/wishlist'">
                                        {{ $t('Wishlist') }}
                                        <i class="icon sharp-arrow-right-icon"></i>
                                    </router-link>
                                </li>

                                <li>
                                    <router-link :to="'/customer/account/orders'">
                                        {{ $t('Orders') }}
                                        <i class="icon sharp-arrow-right-icon"></i>
                                    </router-link>
                                </li>

                                <li>
                                    <router-link :to="'/customer/account/addresses'">
                                        {{ $t('Address Book') }}
                                        <i class="icon sharp-arrow-right-icon"></i>
                                    </router-link>
                                </li>

                                <li>
                                    <router-link :to="'/customer/account/reviews'">
                                        {{ $t('Product Reviews') }}
                                        <i class="icon sharp-arrow-right-icon"></i>
                                    </router-link>
                                </li>
                            </ul>
                        </div>

                        <div class="drawer-box preference" v-if="currencies.length > 1 || locales.length > 1">
                            <h2>{{ $t('Preference') }}</h2>

                            <ul>
                                <li v-if="currencies.length > 1" @click="handleToggleDrawer(); bottomSheets.currency = true">
                                    {{ $t('Currency -') }} {{ currentCurrency.name }} ({{ currentCurrency.code }})
                                    <i class="icon sharp-arrow-right-icon"></i>
                                </li>

                                <li v-if="locales.length > 1" @click="handleToggleDrawer(); bottomSheets.locale = true">
                                    {{ $t('Language -') }} {{ currentLocale.name }} ({{ currentLocale.code }})
                                    <i class="icon sharp-arrow-right-icon"></i>
                                </li>
                            </ul>
                        </div>

                        <div class="drawer-box logout" v-if="currentUser">
                            <button class="logout-btn" @click="logout">{{ $t('Log Out') }}</button>
                        </div>

                    </div>

                </div>
            </drawer-sidebar>

            <bottom-sheet v-if="currencies.length > 1" :show="bottomSheets.currency" @onBottomSheetClose="bottomSheets.currency = false; ">
                <div slot="header">
                   {{ $t('Currency') }}
                </div>

                <div slot="content">
                    <ul>
                        <li v-for="currency in currencies">
                            <span class="radio" :class="currency.code">
                                <input type="radio" :id="currency.code" name="currency" :checked="currency.code == currentCurrency.code" @change="switchCurrency(currency)">
                                <label class="radio-view" :for="currency.code"></label>
                                {{ currency.name }}
                            </span>
                        </li>
                    </ul>
                </div>
            </bottom-sheet>

            <bottom-sheet v-if="locales.length > 1" :show="bottomSheets.locale" @onBottomSheetClose="bottomSheets.locale = false; ">
                <div slot="header">
                    {{ $t('Languages') }}
                </div>

                <div slot="content">
                    <ul>
                        <li v-for="locale in locales">
                            <span class="radio" :class="locale.code">
                                <input type="radio" :id="locale.code" name="locale" :checked="locale.code == currentLocale.code" @change="switchLocale(locale)">
                                <label class="radio-view" :for="locale.code"></label>
                                {{ locale.name }}
                            </span>
                        </li>
                    </ul>
                </div>
            </bottom-sheet>

            <router-view></router-view>
        </div>

        <ajax-loader></ajax-loader>
    </div>
</template>

<script>
    import BottomSheet   from './shared/bottom-sheet';
    import HeaderNav     from './layouts/header-nav';
    import AjaxLoader    from './common/ajax-loader';
    import DrawerSidebar from './common/drawer-sidebar';

    export default {
        name: 'app',

        components: { HeaderNav, BottomSheet, AjaxLoader, DrawerSidebar },

        data () {
			return {
				categories: [],

                currencies: window.config.currencies,

                currentCurrency: window.config.currentCurrency,

                locales: window.config.locales,

                currentLocale: window.config.currentLocale,

                bottomSheets: {
                    currency: false,
                    locale: false
                },

                currentUser: false
			}
		},

        mounted () {
            this.currentUser = JSON.parse(localStorage.getItem('currentUser'));

            var this_this = this;

            EventBus.$on('user-logged-in', function(user) {
                this_this.currentUser = user;
            });

            EventBus.$on('user-logged-out', function() {
                this_this.currentUser = null;
            });

            this.getCategories();
        },

        watch: {
            $route (to, from) {
                this.$refs.drawer.close();
            }
        },

        methods: {
            handleToggleDrawer () {
				if (this.$refs.drawer.active) {
					this.$refs.drawer.close();
				} else {
					this.$refs.drawer.open();
				}
			},

            getCategories () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/descendant-categories", { params: { parent_id: window.channel.root_category_id } })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.categories = response.data.data;
                    })
                    .catch(function (error) {});
            },

            switchCurrency (currency) {
                this.bottomSheets.currency = false;

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/switch-currency", { params: { currency: currency.code } })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        window.location.reload()
                    })
                    .catch(function (error) {});
            },

            switchLocale (locale) {
                this.bottomSheets.locale = false;

                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/switch-locale", { params: { locale: locale.code } })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.$i18n.locale = locale.code;

                        window.location.reload()
                    })
                    .catch(function (error) {});
            },

            logout () {
                this.handleToggleDrawer();

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/customer/logout")
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        localStorage.removeItem('currentUser');

                        EventBus.$emit('user-logged-out');
                    });
            }
        }
    }
</script>


<style lang="scss">
    @import '~@/_variables.scss';

    .drawer {
        background: #F5F5F5;
        width: 100%;
        position: absolute;
        top: 0;
        bottom: 0;
        overflow-y: auto;

        .drawer-header {
            height: 132px;
            position: relative;

            .login-info {
                position: absolute;
                bottom: 0;
                padding: 15px;
                width: 100%;
                        color: #ffffff;

                .avatar {
                    width: 48px;
                    height: 48px;
                    float: left;
                    border-radius: 50%;
                    margin-right: 15px;
                }

                h2 {
                    color: #ffffff;
                    margin-top: 7px;
                }

                p {
                    color: #ffffff;
                    font-weight: 600;
                    font-size: 16px;
                }

                .arrow-right-icon {
                    position: absolute;
                    right: 0;
                    bottom: 15px;
                }
            }
        }

        .drawer-content {
            .drawer-box {
                padding: 15px 0px 0px 15px;
                background: #ffffff;
                margin-bottom: 15px;

                &:last-child {
                    margin-bottom: 0;
                }

                h2 {
                    font-size: 12px;
                    color: $font-light-black-color;
                    text-transform: uppercase;
                    margin-bottom: 5px;
                }

                ul {
                    li {
                        border-bottom: 1px solid rgba(0,0,0,0.08);
                        font-size: 14px;
                        font-weight: 600;

                        a {
                            color: rgba(0,0,0,0.86);
                            padding: 15px 15px 15px 0px;
                            display: block;
                        }

                        &:last-child {
                            border-bottom: 0;
                        }

                        .sharp-arrow-right-icon {
                            float: right;
                            opacity: 0.16;
                        }
                    }
                }

                &.preference {
                    ul {
                        li {
                            padding: 15px 15px 15px 0px;
                        }
                    }
                }

                &.logout {
                    padding: 15px;

                    button.logout-btn {
                        text-transform: uppercase;
                        border: 3px solid #000000;
                        font-size: 14px;
                        font-weight: 600;
                        color: #000000;
                        width: 100%;
                        background: #ffffff;
                        padding: 15px;
                    }
                }
            }
        }
    }
</style>