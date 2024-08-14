<template>
    <div class="content">
        <custom-header :title="$t('Compare')"></custom-header>

        <div class="panel" v-if="compare.length">
            <div class="panel-content">
                <table class="row">
                    <tr v-for="attribute in comparableAttributes" class="compare-products">
                        <td class="attribute-name">
                            <span class="fs16">{{ attribute['name'] ? attribute['name'] : attribute['admin_name'] }}</span>
                        </td>
                        <compare-card
                            v-for="item in compare"
                            :key='item.id'
                            :compareItem="item"
                            :attribute="attribute"
                            :customer="iscustomer"
                            @onRemove="removecompareItem(item)"
                            @onMoveToCart="moveToCart(item)"
                        >
                        </compare-card>
                    </tr>
                </table>
            </div>
        </div>

        <empty-compare v-else></empty-compare>
    </div>
</template>

<script>
    import CustomHeader  from '../layouts/custom-header';
    import CompareCard  from './card';
    import EmptyCompare from './empty-compare';

    export default {
        name: 'compare',

        components: { CustomHeader, CompareCard, EmptyCompare },

        data () {
            return {
                iscustomer: false,
                customer:null,
                compare: [],
                comparableAttributes: []
            }
        },

        mounted () {
            this.getCustomer();

            this.getcompare();
        },

        methods: {
            getCustomer () {
                if (JSON.parse(localStorage.getItem('currentUser'))) {
                    this.customer = JSON.parse(localStorage.getItem('currentUser'));
                    this.iscustomer = true;
                }
            },

            getcompare () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                let url = '/api/pwa/comparison/get-products';
                let items = JSON.parse(localStorage.getItem('compared_product'));
                let data = {
                        params: {
                            'product_ids': items
                        }
                    };

                if ( this_this.iscustomer) {
                    data = {
                        params: {
                            'customer_id': this.customer.id,
                        }
                    };
                }

                if (this_this.iscustomer || (! this_this.iscustomer && items != "")) {
                    this_this.$http.get(url, data)
                    // .then(response => response.json())
                    .then(response => {
                        EventBus.$emit('hide-ajax-loader');

                        if (response.data.data) {
                            this.compare = response.data.data;
                            // this_this.comparableAttributes = response.data.comparableAttributes;
                            this.comparableAttributes = [
                                {
                                    name:'Image',
                                    code: 'product_image'
                                },
                                {
                                    name: 'name',
                                    code: 'name'
                                },
                                {
                                    name: 'price',
                                    code: 'price'
                                },
                                {
                                    name: 'Description',
                                    code: 'description'
                                },
                                {
                                    name: '',
                                    code: 'addToCartHtml'
                                },
                            ];

                        }
                    })
                    .catch(error => {
                        EventBus.$emit('hide-ajax-loader');
                    });
                } else {
                    EventBus.$emit('hide-ajax-loader');
                }
            },

            removecompareItem (item) {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                if (this_this.iscustomer) {
                    this_this.$http.post('/api/pwa/comparison', {
                        'customer_id': this.customer.id,
                        'product_id': item.id,
                    })
                    .then(function(response) {
                        var index = this_this.compare.indexOf(item);

                        this_this.compare.splice(index, 1);

                        this_this.$toasted.show(response.data.message, { type: 'success' })
                    })
                    .catch(function (error) {
                        this_this.$toasted.show('Something went wrong, Please try againg later', { type: 'error' })
                    });
                } else {

                    let existingItems = JSON.parse(localStorage.getItem('compared_product'));

                    EventBus.$emit('hide-ajax-loader');

                    var index = this_this.compare.indexOf(item);

                    var index_local_storage = existingItems.indexOf(item.id)

                    this_this.compare.splice(index, 1);

                    existingItems.splice(index_local_storage, 1);

                    localStorage.setItem('compared_product', JSON.stringify(existingItems));

                    this_this.$toasted.show('Item removed from compare list Succesfully', { type: 'success' })
                }

                EventBus.$emit('hide-ajax-loader');
            },

            moveToCart (item) {
                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/v1/pwa/comparison/move-to-cart/' + item.id)
                    .then(response => {

                        this.$toasted.show(response.data.message, { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                    })
                    .catch(error => {
                        if (error.response.data.data == -1) {
                            this.$router.push({ path: '/products/' + item.product.id })
                        } else if (error.response.data.message) {
                            this.$router.push({ path: '/products/' + item.product.id })

                            this.$toasted.show(error.response.data.message, { type: 'error' })
                        }
                    });
            }
        }
    }
</script>

<style scoped lang="scss">
    .content {
        position: absolute;
        bottom: 0;
        top: 0;
        width: 100%;
    }
    .panel {
        overflow:auto;
    }
    .compare-products {
        height:45px;
    }
    .attribute-name {
        padding-right: 35px;
    }
</style>
