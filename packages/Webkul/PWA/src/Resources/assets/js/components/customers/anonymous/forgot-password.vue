<template>
    <div class="content">
        <custom-header title="Forgot Password"></custom-header>

        <div class="form-container">
            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                <input type="text" name="email" class="control" v-validate="'required|email'" placeholder="Email Address" data-vv-as='"Email Address"'/>
                <label>Email Address</label>
                <span class="control-error" v-if="errors.has('email')">{{ errors.first('email') }}</span>
            </div>

            <div class="button-group">
                <button class="btn btn-black btn-lg">Submit</button>
                
                <router-link class="btn btn-outline-black btn-lg" :to="'/customer/login'">Sign In</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomHeader from '../../layouts/custom-header';

    export default {
        name: 'forgot-password',

        components: { CustomHeader }
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