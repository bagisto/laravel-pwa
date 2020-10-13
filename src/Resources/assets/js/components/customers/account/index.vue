<template>
    <div>
        <router-view v-if="customer" :customer="customer"></router-view>
    </div>
</template>

<script>
    export default {
        name: 'account',

        data () {
            return {
                customer: null
            }
        },

        mounted () {
            this.getAuthCustomer();
        },

        methods: {
            getAuthCustomer () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/customer/get')
                    .then(function(response) {
                        this_this.customer = response.data.data;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            }
        }
    }
</script>

<style scoped lang="scss">
</style>