<template>
    <div class="gallery-images" dir="ltr">
        <carousel
            :per-page="1"
            pagination-color="#E8E8E8"
            pagination-active-color="#979797"
        >
            <slide
                :key='image.uid'
                v-if="product.images.length"
                v-for="image in product.images"
            >
                <div>
                    <video v-if="image.type == 'video'" :src="image.small_image_url" type="video/mp4" width="100%" height="200px" controls>
                        Not Supported
                    </video>

                    <img v-else
                        alt="original-image-url"
                        :src="image.original_image_url"
                    />
                </div>
            </slide>

            <slide v-if="! product.images.length">
                <img
                    alt="base-image-original"
                    :src="product.base_image.original_image_url"
                />
            </slide>
        </carousel>
    </div>
</template>

<script>
    import { Carousel, Slide } from 'vue-carousel';
    export default {
        name: 'gallery-images',

        components: { Carousel, Slide },

        props: ['product'],

        mounted () {
            console.log(this.product);
        }
    }
    
</script>

<style lang="scss" scoped>
    .VueCarousel-wrapper {
        img {
            max-width: 275px;
            max-height: 275px;
        }
    }
</style>