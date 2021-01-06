<template>
    <div class="header">
        <slot name="back-botton">
            <i class="icon back-icon" @click="handleBack"></i>
        </slot>

        <slot name="content" class="content-section">
            <h2 class="display-in-bl">{{ title }}</h2>
        </slot>

        <slot name="navbar-right">
            <router-link class="home-icon" :to="'/'">
                <i class="icon home-icon"></i>
            </router-link>
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
        max-height: 56px;

        .icon {
            float: left;
            cursor: pointer;
            margin-right: 32px;
        }

        .home-icon {
            float: right;
            margin-right: 7px;
        }
        
        .content-section {
            display: inline-block;
        }
    }
</style>