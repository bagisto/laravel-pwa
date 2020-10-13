<template>
    <div class="content">
        <custom-header :title="$t('No Internet')">
            <div slot="back-botton">
                <i class="icon back-icon" @click="$router.push({name: 'home'})"></i>
            </div>

            <div slot="content">
                <h2>{{ $t('No Internet') }}</h2>
            </div>
        </custom-header>

        <div class="offline">
            <div class="illustration offline-img"></div>

            <h2>{{ $t('Opps!') }}</h2>

            <p>{{ $t('You are not connected to the internet, please try again in some time.') }}</p>
        </div>
    </div>
</template>

<script>
    import CustomHeader from '../layouts/custom-header';

    export default {
        name: 'offline',

        components: { CustomHeader },

        mounted () {
            EventBus.$emit('destroy-ajax-loader');
        }
    }
</script>

<style lang="scss">
    .content {
        position: absolute;
        bottom: 0;
        top: 0;
        left: 0;
        width: 100%;

        .offline {
            text-align: center;
            padding-top: 100px;
            padding-bottom: 100px;

            h2 {
                margin-bottom: 8px;
            }

            p {
                font-size: 14px;
                color: rgba(0, 0, 0, 0.56);
            }
        }
    }
</style>