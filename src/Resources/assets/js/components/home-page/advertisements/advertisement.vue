<template>
    <div class="advertisement advertisement-common">
        <div class="advertisement-container" v-if="firstImageUrl">
            <img :src="firstImageUrl" />
        </div>

        <div class="advertisement-container" v-if="secondImageUrl">
            <img :src="secondImageUrl" />
        </div>

        <div class="advertisement-container" v-if="thirdImageUrl">
            <img :src="thirdImageUrl" />
        </div>

        <div class="advertisement-container" v-if="fourthImageUrl">
            <img :src="fourthImageUrl" />
        </div>
    </div>
</template>

<script>
    export default {
        name: 'advertisement',

        props: [
            'firstImageUrl',
            'secondImageUrl',
            'thirdImageUrl',
            'fourthImageUrl'
        ],

        methods: {
            
        }
    }
</script>

<style lang="scss" scoped>
    img {
        width: 100%;
        padding: 10px;
    }
</style>