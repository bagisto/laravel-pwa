<template>
    <div class="gallery-images">
        <carousel :per-page="1" pagination-active-color="#979797" pagination-color="#E8E8E8">
            <slide v-if="product.images.length" v-for="image in product.images" :key='image.uid'>
                <img alt="original-image-url" :src="image.original_image_url"/>
            </slide>

            <slide v-if="! product.images.length">
                <img alt="base-image-original" :src="product.base_image.original_image_url"/>
            </slide>
        </carousel>
    </div>
</template>

<script>
    import { Carousel, Slide } from 'vue-carousel';

    export default {
        name: 'gallery-images',

        components: { Carousel, Slide },

        props: ['product']
    }
</script>

<style lang="scss">
    .VueCarousel-wrapper {
        background: #F2F2F2;
        text-align: center;

        img {
            max-width: 275px;
            max-height: 275px;
        }
    }

    .VueCarousel-pagination {
        position: absolute;
        bottom: 10px;

        .VueCarousel-dot {
            background: rgba(0, 0, 0, 0.16) !important;
            border: 2px solid rgba(255, 255, 255, 0.86) !important;
            border-radius: 8px !important;
            width: 6px !important;
            height: 6px !important;
            padding: 0 !important;
            vertical-align: middle;
            margin-right: 9px;

            &.VueCarousel-dot--active {
                width: 8px !important;
                height: 8px !important;
                background: #FFFFFF !important;
                border: 2px solid rgba(0, 0, 0, 0.16) !important;
            }
        }
    }
</style>