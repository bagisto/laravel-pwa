<template>
    <div class="content">
        <custom-header>
            <div slot="back-botton" @click="$emit('onPopClose')">
                <i class="icon sharp-cross-icon"></i>
            </div>

            <div slot="content">
                <h2>{{ $t('Forgot Password') }}</h2>
            </div>
        </custom-header>

        <form action="POST" @submit.prevent="validateBeforeSubmit">
            <div class="form-container">
                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <input type="text" name="email" class="control" v-model="user.email" v-validate="'required|email'" :placeholder="$t('Email Address')" :data-vv-as="$t('Email Address')"/>
                    <label>{{ $t('Email Address') }}</label>
                    <span class="control-error" v-if="errors.has('email')">{{ errors.first('email') }}</span>
                </div>

                <div class="button-group">
                    <button class="btn btn-black btn-lg">{{ $t('Submit') }}</button>
                    
                    <button class="btn btn-outline-black btn-lg" @click="$emit('onOpenPopup', 'login')">{{ $t('Sign In') }}</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import CustomHeader from '../../layouts/custom-header';

    export default {
        name: 'forgot-password',

        components: { CustomHeader },

        data () {
            return {
                loading: false,

                user: {
                    'email': ''
                }
            }
        },

        methods: {
            validateBeforeSubmit () {
                this.loading = true;

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.forgotPassword()
                    } else {
                        this.loading = false;
                    }
                });
            },

            forgotPassword () {
                var this_this = this;
                
                EventBus.$emit('show-ajax-loader');

                this.$http.post("/api/customer/forgot-password", this.user)
                    .then(function(response) {
                        this_this.loading = false;

                        EventBus.$emit('hide-ajax-loader');

                        if (response.data.error) {
                            this_this.$toasted.show(response.data.error, { type: 'error' });
                        } else {
                            this_this.$toasted.show(response.data.message, { type: 'success' });

                            this_this.$emit('onPopClose');
                        }
                    })
                    .catch(function (error) {
                        var errors = error.response.data.errors;

                        for (name in errors) {
                            if (errors.hasOwnProperty(name)) {
                                this_this.errors.add(name, errors[name][0]);
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
        }
    }
</style>