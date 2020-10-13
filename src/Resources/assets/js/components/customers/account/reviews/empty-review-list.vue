<template>
    <div class="no-result-found">
        <div class="illustration empty-reviews-img"></div>

        <h2>{{ $t('No Reviews') }}</h2>

        <p>{{ $t('You didn’t review any of the items yet.') }}</p>
        <!-- just change the route to /customer/account/reviews to redirect to review page -->
        <!-- but in this case it is correct and no changes are required -->
        <router-link class="btn btn-black" :to="'/customer/account/orders'">{{ $t('View Order and Review') }}</router-link>
        <!--end-->
    </div>
</template>

<script>
    export default {
        name: 'empty-review-list'
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