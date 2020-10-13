<template>
    <div class="footer">
        <span class="sepator"></span>

        <p>{{ $t('You have just reached to the bottom of page.') }}</p>

        <a @click.prevent="backToTop">{{ $t('Back To Top') }}</a>
    </div>
</template>

<script>
    export default {
        name: 'footer-nav',

        methods: {
            backToTop () {
                $("html, body").animate({scrollTop: 0}, 1000);
            }
        }
    }
</script>

<style lang="scss">
    .footer {
        padding: 10px 0;
        text-align: center;

        .sepator {
            display: inline-block;
            border-bottom: 1px solid #F5F5F5;;
            width: 100px;
            margin-bottom: 10px;
        }

        p {
            font-size: 10px;
            margin-bottom: 10px;
            color: #757575;
        }

        a {
            font-size: 10px;
            color: #000000;
            text-transform: uppercase;
        }
    }
</style>