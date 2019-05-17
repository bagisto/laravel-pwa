<template>
    <div class="content">
        <custom-header>
            <div slot="back-botton" @click="$emit('onPopClose')">
                <i class="icon sharp-cross-icon"></i>
            </div>

            <div slot="content">
                <h2>Create An Account</h2>
            </div>
        </custom-header>

        <div class="form-container">
            <form action="POST" @submit.prevent="validateBeforeSubmit">
                <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <input type="text" name="first_name" class="control" v-model="user.first_name" v-validate="'required'" placeholder="First Name" data-vv-as='"First Name"'/>
                    <label>First Name</label>
                    <span class="control-error" v-if="errors.has('first_name')">{{ errors.first('first_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                    <input type="text" name="last_name" class="control" v-model="user.last_name" v-validate="'required'" placeholder="Last Name" data-vv-as='"Last Name"'/>
                    <label>Last Name</label>
                    <span class="control-error" v-if="errors.has('last_name')">{{ errors.first('last_name') }}</span>
                </div>

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

                <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                    <input type="password" name="password_confirmation" class="control" v-model="user.password_confirmation" v-validate="'required|min:6|confirmed:password'" placeholder="Confirm Password" data-vv-as='"Confirm Password"'/>
                    <label>Confirm Password</label>
                    <span class="control-error" v-if="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
                    <div class="control-info success">
                        <span class="dot"></span>

                        <span class="info">
                            Minimum length of this field must be equal or greater than 8 symbols. 
                        </span>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-black btn-lg" :disabled="loading">Create An Account</button>
                </div>
            </form>

            <div class="login-in-text">
                Already have an account? <span @click="$emit('onOpenPopup', 'login')">Sign In</span>
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