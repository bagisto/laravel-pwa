<template>
    <div class="address-card">
        <div class="address-deatils">
            {{ address.address1.join(' ') }}</br>
            {{ address.city }}</br>
            {{ address.state }}</br>
            {{ address.country_name + ' ' + address.postcode }}</br>
            {{ address.phone }}

            <!-- <i class="icon sharp-arrow-right-icon"></i> -->
        </div>

        <div class="address-actions">
            <router-link class="edit-link" :to="'/customer/account/addresses/' + address.id">
                <i class="icon sharp-edit-icon"></i>

                <span>{{ $t('Edit') }}</span>
            </router-link>

            <div class="remove-link" @click="remove">
                <i class="icon sharp-trash-icon"></i>

                <span>{{ $t('Remove') }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'address-list',

        props: ['address'],

        methods: {
            remove () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.delete('/api/addresses/' + this.address.id)
                    .then(function(response) {
                        this_this.$toasted.show('Address Removed Successfully', { type: 'success' })

                        EventBus.$emit('hide-ajax-loader');

                        this_this.$emit('onRemove')
                    })
                    .catch(function (error) {});
            }
        }
    }
</script>

<style scoped lang="scss">
    .address-card {
        position: relative;
        background: #ffffff;
        margin-bottom: 28px;

        .address-deatils {
            padding: 16px;
            font-size: 14px;

            .sharp-arrow-right-icon {
                position: absolute;
                right: 16px;
                bottom: 50%;
                margin-bottom: 12px;
                opacity: 0.16;
            }
        }

        .address-actions {
            border: 1px solid rgba(0, 0, 0, 0.12);
            font-weight: 600;
            font-size: 14px;

            .edit-link {
                float: left;
                width: 50%;
                display: inline-block;
                padding: 16px;
                cursor: pointer;
                color: rgba(0, 0, 0, 0.87);

                .icon {
                    float: left;
                    margin-right: 8px;
                    opacity: 0.56;
                }

                span {
                    line-height: 24px;
                }
            }

            .remove-link {
                width: 50%;
                display: inline-block;
                padding: 16px;
                cursor: pointer;
                color: rgba(0, 0, 0, 0.87);

                .icon {
                    float: left;
                    margin-right: 8px;
                    opacity: 0.56;
                }

                span {
                    line-height: 24px;
                }
            }
        }
    }
</style>