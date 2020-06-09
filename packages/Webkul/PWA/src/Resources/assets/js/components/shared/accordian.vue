<template>
    <div class="accordian" :class="[isActive ? 'active' : '', className]" :id="id">
        <div class="accordian-header" @click="toggleAccordion()">
            <slot name="header">
                {{ title }}
                <i class="icon" :class="iconClass"></i>
            </slot>
        </div>

        <div class="accordian-content" v-bind:style="{ padding: padding + 'px' }">
            <slot name="body">
            </slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title: String,

            id: String,

            className: String,

            padding: String,

            active: Boolean
        },

        data () {
            return {
                isActive: false
            }
        },

        mounted () {
            this.isActive = this.active;
        },

        methods: {
            toggleAccordion () {
                this.isActive = ! this.isActive;
            }
        },

        computed: {
            iconClass () {
                return {
                    'accordian-down-icon': ! this.isActive,
                    'accordian-up-icon': this.isActive,
                };
            }
        }
    }
</script>

<style scoped lang="scss">
    .accordian {
        display: inline-block;
        width: 100%;
        margin-bottom: 12px;
        background: #ffffff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);

        .accordian-header {
            width: 100%;
            display: inline-block;
            border-bottom: 0;
            padding: 14px 16px;
            cursor: pointer;
            margin-top: -1px;
            text-transform: uppercase;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.6);
            font-weight: 700;
            line-height: 24px;

            .icon {
                float: right;

                &.left {
                    float: left;
                }
            }
        }

        .accordian-content {
            width: 100%;
            padding: 16px;
            display: none;
            transition: 0.3s ease all;
        }

        &.active > .accordian-content {
            display: inline-block;
        }

        &.active > .accordian-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.12) !important;
        }
    }
</style>