<template>
    <div class="content">
        <custom-header>
            <div slot="back-botton" @click="$emit('onPopClose')">
                <i class="icon sharp-cross-icon"></i>
            </div>

            <div slot="content">
                <h2>{{ $t('Create An Account') }}</h2>
            </div>
        </custom-header>

        <div class="form-container">
            <form action="POST" @submit.prevent="validateBeforeSubmit">
                <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <input type="text" name="first_name" class="control" v-model="user.first_name" v-validate="'required'" :placeholder="$t('First Name')" :data-vv-as="$t('First Name')"/>
                    <label>{{ $t('First Name') }}</label>
                    <span class="control-error" v-if="errors.has('first_name')">{{ errors.first('first_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                    <input type="text" name="last_name" class="control" v-model="user.last_name" v-validate="'required'" :placeholder="$t('Last Name')" :data-vv-as="$t('Last Name')"/>
                    <label>{{ $t('Last Name') }}</label>
                    <span class="control-error" v-if="errors.has('last_name')">{{ errors.first('last_name') }}</span>
                </div>

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

                <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                    <input type="password" name="password_confirmation" class="control" v-model="user.password_confirmation" v-validate="'required|min:6|confirmed:password'" :placeholder="$t('Confirm Password')" :data-vv-as="$t('Confirm Password')"/>
                    <label>{{ $t('Confirm Password') }}</label>
                    <span class="control-error" v-if="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
                    <div class="control-info success">
                        <span class="dot"></span>

                        <span class="info">
                            {{ $t('Minimum length of this field must be equal or greater than 6 characters.') }}
                        </span>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-black btn-lg" :disabled="loading">{{ $t('Create An Account') }}</button>
                </div>
            </form>

            <div class="login-in-text">
                {{ $t('Already have an account?') }} <span @click="$emit('onOpenPopup', 'login')">{{ $t('Sign In') }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomHeader from '../../layouts/custom-header';

    export default {
        name: 'register',

        components: { CustomHeader },

        data () {
            return {
                loading: false,

                user: {
                    'first_name': '',
                    'last_name': '',
                    'email': '',
                    'password': '',
                    'password_confirmation': ''
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
                        this.registerCustomer()
                    } else {
                        this.loading = false;
                    }
                });
            },

            registerCustomer () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.post("/api/customer/register", this.user)
                    .then(function(response) {
                        this_this.loading = false;

                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        this_this.$emit('onOpenPopup', 'login')
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
        background: #fff;

        .form-container {
            padding: 24px 16px;
            margin-top: 55px;

            .login-in-text {
                margin-top: 44px;
                text-align: center;
                font-weight: 700;
                text-transform: uppercase;
                color: #757575;

                span {
                    color: #3F60DA;
                    cursor: pointer;
                }
            }
        }
    }
</style>