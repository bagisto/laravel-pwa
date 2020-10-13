<template>
    <div class="content">
        <custom-header>
            <div slot="back-botton" @click="handleBack">
                <i class="icon back-icon"></i>
            </div>

            <div slot="content">
                <h2>{{ $t('Sign In or Register') }}</h2>
            </div>
        </custom-header>

        <div class="form-container">
            <div class="shop-title">{{ app_name }}</div>

            <h3>{{ $t('Sign In or Register') }}</h3>

            <div class="button-group">
                <button class="btn btn-outline-black" @click="popups.login = true">{{ $t('Sign In') }}</button>

                <button class="btn btn-outline-black" @click="popups.register = true">{{ $t('Create An Account') }}</button>
            </div>
        </div>

        <login 
            v-if="popups.login"
            @onPopClose="popups.login = false"
            @onOpenPopup="openPopup($event)"
        ></login>

        <register
            v-if="popups.register"
            @onPopClose="popups.register = false"
            @onOpenPopup="openPopup($event)"
        ></register>

        <forgot-password
            v-if="popups.forgot_password"
            @onPopClose="popups.forgot_password = false"
            @onOpenPopup="openPopup($event)"
        ></forgot-password>
    </div>
</template>

<script>
    import CustomHeader   from '../../layouts/custom-header';
    import Login          from './login';
    import Register       from './register';
    import ForgotPassword from './forgot-password';

    export default {
        name: 'login-register',

        components: { CustomHeader, Login, Register, ForgotPassword },

        data () {
            return {
                channel: window.channel,

                popups: {
                    login: false,
                    register: false,
                    forgot_password: false
                },
            }
        },

        computed: {
            app_name () {
                return window.config.app_short_name ? window.config.app_short_name : 'Bagisto';
            }
        },

        methods: {
            openPopup (value) {
                this.popups = { login: false, register: false, forgot_password: false };

                this.popups[value] = true;
            },

            handleBack () {
                this.$router.push({name: 'home'})
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
        background: #fff;

        .form-container {
            padding: 0 16px;
            padding-top: 100px;
            padding-bottom: 100px;
            margin-top: 55px;
            text-align: center;

            .shop-title {
                font-size: 32px;
                font-weight: 700;
                margin-bottom: 100px;
            }

            h3 {
                font-weight: 700;
                font-size: 12px;
                color: rgba(0, 0, 0, 0.56);
                text-transform: uppercase;
                margin-bottom: 24px;
            }

            .button-group {
                button {
                    border: 3px solid #000000;
                    font-weight: 700;
                }
            }
        }
    }
</style>