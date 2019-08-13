<template>
    <div class="content">
        <custom-header>
            <div slot="back-botton" @click="$emit('onPopClose')">
                <i class="icon sharp-cross-icon"></i>
            </div>

            <div slot="content">
                <h2>{{ $t('Sign In') }}</h2>
            </div>
        </custom-header>

        <form action="POST" @submit.prevent="validateBeforeSubmit">
            <div class="form-container">
                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <input type="text" name="email" class="control" v-model="user.email" v-validate="'required|email'" :placeholder="$t('Email Address')" :data-vv-as="$t('Email Address')"/>
                    <label>{{ $t('Email Address') }}</label>
                    <span class="control-error" v-if="errors.has('email')">{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <input type="password" name="password" class="control" v-model="user.password" v-validate="'required|min:6'" :placeholder="$t('Password')" :data-vv-as="$t('Password')"/>
                    <label>{{ $t('Password') }}</label>
                    <span class="control-error" v-if="errors.has('password')">{{ errors.first('password') }}</span>
                </div>

                <div class="control-group">
                    <span class="forgot-password" @click="$emit('onOpenPopup', 'forgot_password')">{{ $t('Forgot Password?') }}</span>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-black btn-lg" :disabled="loading">{{ $t('Sign In') }}</button>

                    <button type="button" class="btn btn-outline-black btn-lg" @click="$emit('onOpenPopup', 'register')">{{ $t('Create An Account') }}</button>
                     <span style="color: red;" id="login-error"></span>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import CustomHeader from '../../layouts/custom-header';

    export default {
        name: 'login',

        components: { CustomHeader },

        data () {
            return {
                loading: false,

                user: {
                    'email': '',
                    'password': ''
                }
            }
        },

        mounted () {
            if (JSON.parse(localStorage.getItem('currentUser')))
                this.$router.push({name: 'dashboard'})
        },

        methods: {
            validateBeforeSubmit () {
                this.loading = true;

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.loginCustomer()
                    } else {
                        this.loading = false;
                    }
                });
            },

            loginCustomer () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.post("/api/customer/login", this.user)
                    .then(function(response) {
                        this_this.loading = false;

                        EventBus.$emit('hide-ajax-loader');

                        localStorage.setItem('currentUser', JSON.stringify(response.data.data));

                        EventBus.$emit('user-logged-in', response.data.data);

                        this_this.$router.push({name: 'dashboard'})
                    })
                    .catch(function (error) {
                        var errors = error.response.data.errors;
                        // place your error here
                        document.getElementById("login-error").innerHTML = "Incorrect Credentials";
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
        background: #fff;

        .form-container {
            padding: 24px 16px;
            margin-top: 55px;

            .control-group {
                .forgot-password {
                    font-weight: 600;
                    font-size: 14px;
                    color: #757575;
                    cursor: pointer;
                }
            }
        }
    }
</style>