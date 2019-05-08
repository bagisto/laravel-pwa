<template>
    <div class="loader-overlay" v-if="requestCount">
        <div class="fade-overlay"></div>

        <div class="main-loader">
            <div id="loader" class="cp-spinner cp-round"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ajax-loader',

        data () {
			return {
                requestCount: 0
            }
        },

        created () {
            var this_this = this;

            EventBus.$on('show-ajax-loader', function() {
                this_this.requestCount++;
            });

            EventBus.$on('hide-ajax-loader', function() {
                if (this_this.requestCount)
                    this_this.requestCount--;
            });

            EventBus.$on('destroy-ajax-loader', function() {
                this_this.requestCount = 0;
            });
        }
    }
</script>

<style scoped lang="scss">
    .loader-overlay {
        height: 100%;
        left: 0;
        position: fixed;
        text-align: center;
        top: 0;
        width: 100%;
        z-index: 100;

        .fade-overlay {
            background: #fff;
            height: 100%;
            opacity: 0.9;
            position: fixed;
            text-align: center;
            width: 100%;
            z-index: 10;
        }

        .main-loader {
            opacity: 1;
            position: fixed;
            z-index: 10000;
            width: 100%;
            height: 100%;
            padding-bottom: 15px;

            .cp-spinner {
                width: 30px;
                height: 30px;
                top: 50%;
                display: inline-block;
                box-sizing: border-box;
                position: relative;

                &.cp-round {
                    &:before {
                        border-radius: 50%;
                        content: " ";
                        width: 30px;
                        height: 30px;
                        display: inline-block;
                        box-sizing: border-box;
                        border-top: solid 5px #bababa;
                        border-right: solid 5px #bababa;
                        border-bottom: solid 5px #bababa;
                        border-left: solid 5px #bababa;
                        position: absolute;
                        top: 0;
                        left: 0;
                    }

                    &:after {
                        border-radius: 50%;
                        content: " ";
                        width: 30px;
                        height: 30px;
                        display: inline-block;
                        box-sizing: border-box;
                        border-top: solid 5px #000000;
                        border-right: solid 5px transparent;
                        border-bottom: solid 5px transparent;
                        border-left: solid 5px transparent;
                        position: absolute;
                        top: 0;
                        left: 0;
                        animation: spin 1s ease-in-out infinite;
                    }
                }
            }
        }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>