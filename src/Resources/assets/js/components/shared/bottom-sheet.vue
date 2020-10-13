<template>
	<div class="bottom-sheet" :class="[show ? 'open' : '', className ]">

        <div class="bottom-sheet-body">
            <div class="bottom-sheet-header">
                <slot name="header"></slot>
            </div>

            <div class="bottom-sheet-content">
                <slot name="content"></slot>
            </div>
        </div>

        <div class="glass" @click="close"></div>
		
	</div>
</template>

<script>
    export default {
        name: 'bottom-sheet',

        props: {
            show: Boolean,
            className: String
        },

        methods: {
            close () {
                this.$emit('onBottomSheetClose')
            }
        }
    }
</script>

<style scoped lang="scss">
    .bottom-sheet {
        .glass {
            z-index: 99;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s ease;
        }

        .bottom-sheet-body {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            opacity: 0;
            background: #fff;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, .33);
            transform: translateY(100%);
            z-index: 100;
            transition: opacity .3s ease, transform .3s ease;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            max-height: 100%;

            .bottom-sheet-header {
                text-transform: uppercase;
                font-size: 16px;
                font-weight: 600;
                padding: 16px;
                color: rgba(0, 0, 0, 1);
            }

            .bottom-sheet-content {
                font-weight: 600;
                color: rgba(0, 0, 0, 0.86);
                font-size: 16px;

                li {
                    padding: 16px;
                }
            }
        }

        &.open {
            .bottom-sheet-body {
                opacity: 1;
                transform: translateY(0);
            }

            .glass {
                pointer-events: initial;
                opacity: 1;
            }
        }
    }
</style>