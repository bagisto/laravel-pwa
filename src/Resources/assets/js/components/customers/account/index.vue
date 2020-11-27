<template>
    <div>
        <router-view v-if="customer" :customer="customer"></router-view>
    </div>
</template>

<script>
    import {
        mapState,
        mapActions
    } from 'vuex';
    
    export default {
        name: 'account',
        
        computed: mapState({
            customer: state => state.customer,
        }),

        mounted () {
            this.getCustomer();
        },

        methods: {
            ...mapActions([
                'getCustomer',
            ]),
        }
    }
</script>

<style scoped lang="scss">
</style>