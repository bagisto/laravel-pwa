<template>
    <div class="product-additional-information" v-if="viewableAttributes.length">
        <accordian :title="$t('More Information')" :active="true">
            <div slot="body">

                <table>
                    <tbody>
                        <tr v-for="attribute in viewableAttributes">
                            <td>{{ attribute.label }}</td>
                            
                            <td>{{ attribute.value }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </accordian>
    </div>
</template>

<script>
    import Accordian from '../shared/accordian';

    export default {
        name: 'attributes',
        
        components: { Accordian },

        props: ['product'],

        data () {
			return {
                viewableAttributes: []
            }
        },

        mounted () {
            this.getProductAdditinalInformation(this.product.id);
        },

        methods: {
            getProductAdditinalInformation (productId) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/product-additional-information/' + productId)
                    .then(function(response) {
                        this_this.viewableAttributes = response.data.data;

                        EventBus.$emit('hide-ajax-loader');
                    })
                    .catch(function (error) {});
            }
        }
    }
</script>

<style scoped lang="scss">
    .product-additional-information {
        table {
            width: 100%;
            border-collapse: collapse;

            tr {
                td {
                    padding: 16px;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
                    font-size: 14px;

                    &:first-child {
                        border-right: 1px solid rgba(0, 0, 0, 0.12);
                        font-weight: 600;
                        color: rgba(0, 0, 0, 0.54);
                        text-align: right;
                    }

                    &:last-child {
                        color: rgba(0, 0, 0, 0.87);
                    }
                }

                &:last-child {
                    td {
                        border-bottom: 0;
                    }
                }
            }
        }
    }
</style>