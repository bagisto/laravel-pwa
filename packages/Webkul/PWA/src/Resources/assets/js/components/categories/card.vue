<template>
    <li class="category-card">
        <router-link :to="'/categories/' + category.id">
            <div class="image">
                <img alt="category-image" :src="category.image_url ? category.image_url : 'vendor/webkul/pwa/assets/images/category-image.png'"/>
            </div>

            <div class="name">
                {{ category.name }}
            </div>
        </router-link>
    </li>
</template>

<script>
    export default {
        name: 'category-card',

        props: ['category']
    }
</script>

<style lang="scss" scoped>
    li.category-card {
        display: inline-block;
        width: 120px;
        height: 152px;
        margin-right: 15px;
        position: relative;
        background: #FFFFFF;
        box-shadow: 0 6px 10px 0 rgba(0,0,0,0.08), 0 1px 18px 0 rgba(0,0,0,0.08), 0 3px 5px -1px rgba(0,0,0,0.08);

        &:last-child {
            margin-right: 0;
        }

        .image {
            height: 120px;

            img {
                width: 100%;
                height: 100%;
            }
        }

        .name {
            height: 32px;
            font-size: 12px;
            text-align: center;
            line-height: 32px;
            color: rgba(0, 0, 0, 0.56);
        }
    }
</style>