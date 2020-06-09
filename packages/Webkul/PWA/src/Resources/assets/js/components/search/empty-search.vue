<template>
    <div class="no-result-found">
        <div class="illustration empty-search-img"></div>

        <h2>{{ $t('Umm..') }}</h2>

        <p>{{ $t('No search results found.') }}</p>

        <router-link class="btn btn-black" :to="'/search'">{{ $t('Search Again') }}</router-link>
    </div>
</template>

<script>
    export default {
        name: 'empty-search'
    }
</script>

<style scoped lang="scss">
    .no-result-found {
        text-align: center;
        padding-top: 100px;
        padding-bottom: 100px;

        h2 {
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        p {
            font-size: 14px;
            color: rgba(0, 0, 0, 0.56);
        }

        .btn {
            margin-top: 24px;
        }
    }
</style>