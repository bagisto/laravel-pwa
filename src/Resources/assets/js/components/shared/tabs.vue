<template>
    <div>
        <div class="tabs">
            <ul>
                <li v-for="tab in tabs" :class="{ 'active': tab.isActive }" @click="selectTab(tab)" :style="{width: tabWidth + '%'}">
                    {{ tab.name }}
                </li>
            </ul>
        </div>

        <div class="tabs-content">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'tabs',

        data () {
            return {
                tabs: [],

                tabWidth: 0
            }
        },

        created () {
            this.tabs = this.$children;
        },

        mounted () {
            this.tabWidth = 100 / this.tabs.length;
        },

        methods: {
            selectTab (selectedTab) {
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.name == selectedTab.name);
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .tabs {
        box-shadow: 0 10px 10px 0 rgba(0,0,0,0.04), 0 1px 4px 0 rgba(0,0,0,0.16);
        background: #ffffff;
        margin-bottom: 16px;

        ul {
            li {
                display: inline-block;
                padding: 19px 0;
                cursor: pointer;
                text-align: center;
                font-size: 13px;
                color: rgba(0, 0, 0, 0.56);

                &.active {
                    border-bottom: 3px solid #000000;
                    color: #000000;
                }
            }
        }
    }
</style>