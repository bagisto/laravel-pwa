<template>
    <div class="content">
        <custom-header :title="$t('Edit Profile')"></custom-header>

        <form action="POST" @submit.prevent="validateBeforeSubmit">
            <div class="form-container">
                <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                    <input type="text" name="first_name" class="control" v-model="customer.first_name" v-validate="'required'" :placeholder="$t('First Name')" :data-vv-as="$t('First Name')"/>
                    <label>{{ $t('First Name') }}</label>
                    <span class="control-error" v-if="errors.has('first_name')">{{ errors.first('first_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                    <input type="text" name="last_name" class="control" v-model="customer.last_name" v-validate="'required'" :placeholder="$t('Last Name')" :data-vv-as="$t('Last Name')"/>
                    <label>{{ $t('Last Name') }}</label>
                    <span class="control-error" v-if="errors.has('last_name')">{{ errors.first('last_name') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                    <input type="text" name="email" class="control" v-model="customer.email" v-validate="'required|email'" :placeholder="$t('Email Address')" :data-vv-as="$t('Email Address')"/>
                    <label>{{ $t('Email Address') }}</label>
                    <span class="control-error" v-if="errors.has('email')">{{ errors.first('email') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('gender') ? 'has-error' : '']">
                    <select name="gender" class="control" v-model="customer.gender" v-validate="'required'" :placeholder="$t('Gender')" :data-vv-as="$t('Gender')">
                        <option value="Male">{{ $t('Male') }}</option>
                        <option value="Female">{{ $t('Female') }}</option>
                        <option value="Other">{{ $t('Other') }}</option>
                    </select>

                    <label>{{ $t('Gender') }}</label>
                    <span class="control-error" v-if="errors.has('gender')">{{ errors.first('gender') }}</span>
                </div>

                <div class="control-group">
                    <input type="date" name="date_of_birth" class="control" v-model="customer.date_of_birth" :placeholder="$t('Date Of Birth')"/>
                    <label>{{ $t('Date Of Birth') }}</label>
                </div>

                <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                    <input type="password" name="password" class="control" v-model="customer.password" v-validate="'min:6'" :placeholder="$t('Password')" :data-vv-as="$t('Password')"/>
                    <label>{{ $t('Password') }}</label>
                    <span class="control-error" v-if="errors.has('password')">{{ errors.first('password') }}</span>
                </div>

                <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                    <input type="password" name="password_confirmation" class="control" v-model="customer.password_confirmation" v-validate="'min:6|confirmed:password'" :placeholder="$t('Confirm Password')" :data-vv-as="$t('Confirm Password')"/>
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
                    <button type="submit" class="btn btn-black btn-lg" :disabled="loading">{{ $t('Save') }}</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import CustomHeader from '../../../layouts/custom-header';

    export default {
        name: 'edit-profile',

        props: ['customer'],

        data () {
            return {
                loading: false
            }
        },

        components: { CustomHeader },

        methods: {
            validateBeforeSubmit () {
                this.loading = true;

                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.updateProfile()
                    } else {
                        this.loading = false;
                    }
                });
            },

            updateProfile () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.put("/api/customer/profile", this.customer)
                    .then(function(response) {
                        this_this.loading = false;

                        this_this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        EventBus.$emit('customer-info-updated', this_this.customer);

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
        background: #ffffff;

        .form-container {
            padding: 24px 16px;
            margin-top: 55px;
        }
    }
</style>