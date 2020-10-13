<template>
    <div class="pagination" v-if="current_page < last_page">
        <button @click="gotToPage()">
            {{ label }}
        </button>
    </div>
</template>

<script>
    export default {
        name: 'pagination',

        props: {
            label: String,

            current_page: {
                type: Number,
                default: 1
            },

            from: Number,

            last_page: {
                type: Number,
                default: 1
            },

            per_page: [ String, Number ],

            to: Number,

            total: Number
        },

        methods: {
            gotToPage () {
                this.$emit('onPaginate', this.current_page + 1)
            }
        }
    }
</script>

<style scoped lang="scss">
    .pagination {
        margin-top: 24px;

        button {
            border: 3px solid #000000;
            font-size: 14px;
            font-weight: 600;
            color: #000000;
            width: 100%;
            background: #ffffff;
            padding: 15px;
        }
    }
</style>