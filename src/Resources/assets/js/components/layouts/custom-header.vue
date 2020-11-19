<template>
    <div class="header">
        <slot name="back-botton">
            <i class="icon back-icon" @click="handleBack"></i>
        </slot>

        <slot name="content">
            <h2>{{ title }}</h2>
        </slot>
    </div>
</template>

<script>
    export default {
        name: 'custom-header',

        props: ['title'],

        methods: {
            handleBack () {
                if (
                    window.history.length <= 2
                    || this.$route.name == "dashboard"
                ) {
                    this.$router.push({name: 'home'});
                } else {
                    this.$router.back();
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .header {
        display: block;
        padding: 15px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        position: fixed;
        z-index: 101;
        background: #ffffff;
        width: 100%;
        top: 0;

        .icon {
            float: left;
            cursor: pointer;
            margin-right: 32px;
        }
    }
</style>