<template>
    <div class="content">
        <custom-header title="Sign In"></custom-header>

        <form action="POST" @submit.prevent="validateBeforeSubmit">
            <div class="form-container">
                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <input type="text" name="email" class="control" v-model="user.email" v-validate="'required|email'" placeholder="Email Address" data-vv-as='"Email Address"'/>
                    <label>Email Address</label>
                    <span class="control-error" v-if="errors.has('email')">{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <input type="password" name="password" class="control" v-model="user.password" v-validate="'required|min:6'" placeholder="Password" data-vv-as='"Password"'/>
                    <label>Password</label>
                    <span class="control-error" v-if="errors.has('password')">{{ errors.first('password') }}</span>
                </div>

                <div class="control-group">
                    <router-link :to="'/customer/forgot-password'">Forgot Password?</router-link>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-black btn-lg" :disabled="loading">Sign In</button>
                    
                    <router-link class="btn btn-outline-black btn-lg" :to="'/customer/register'">Create An Account</router-link>
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
                a {
                    font-weight: 600;
                    font-size: 14px;
                    color: #757575;
                }
            }
        }
    }
</style>