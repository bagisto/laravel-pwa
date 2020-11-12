<template>
    <div class="downloadable-products-container">
        <div class="samples-container">
            <h3>{{ $t('downloadable.sample') }}</h3>
            
            <ul>
                <li v-for="(sample, index) in downloadableSamples" class="downloadable-product">
                    <a :href="sample.download_url" target="_blank">
                        {{ sample.title }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="links-container">
            <h3>{{ $t('downloadable.links') }}</h3>
            
            <ul class="control-group" :class="[errors.has('links[]') ? 'has-error' : '']">
                <li v-for="(link, index) in downloadableLinks" class="downloadable-product">
                    <span class="checkbox">
                        <input
                            id="link.id"
                            name="links[]"
                            type="checkbox"
                            :value="link.id"
                            v-validate="'required'"
                            v-model="formData.links[link.id]"
                            :data-vv-as="$t('downloadable.links')"
                        />
                        <label class="checkbox-view" :for="link.id"></label>
                        {{ link.title + ' + ' + link.price }}
                    </span>

                    <a
                        target="_blank"
                        class="link_sample"
                        v-if="link.sample_file"
                        :href="link.sample_download_url"
                    >
                        {{ $t('downloadable.sample') }}
                    </a>
                </li>
            </ul>

            <span class="control-error" v-if="errors.has('links[]')">{{ errors.first('links[]') }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'downloadable-options',

        props: ['product', 'formData'],

        data: function () {
            return {
                config: {},

                downloadableLinks: this.product.downloadable_links,
                downloadableSamples: this.product.downloadable_samples,

                selectedProductId: '',

                simpleProduct: null,

                galleryImages: []
            }
        },

        inject: ['$validator'],

        methods: {
            changeQuantity: function (type, productId) {
                if (type == 'increase') {
                    this.$set(this.localFormData.qty, productId, this.localFormData.qty[productId] + 1);
                } else if (type == 'decrease') {
                    if (this.localFormData.qty[productId] > 1) {
                        this.$set(this.localFormData.qty, productId, this.localFormData.qty[productId] - 1);
                    }
                }
            },

            selectLink: function ({target}) {
                if (target.checked) {

                } else {

                }
            }
        }
    }
</script>