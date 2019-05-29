<template>
    <div class="layered-filter-wrapper">
        <div class="filter" v-sticky>
            <ul>
                <li @click="bottomSheets.sort = true">
                    <i class="icon sharp-sort-icon"></i>
                    {{ $t('Sort') }}
                </li>

                <li @click="bottomSheets.filter = true">
                    <i class="icon sharp-filter-icon"></i>
                    {{ $t('Filter') }}
                </li> 
                <!-- <li>
                    <i class="icon sharp-grid-icon"></i>
                    Grid
                </li> -->
            </ul>
        </div>

        <bottom-sheet :show="bottomSheets.sort" @onBottomSheetClose="bottomSheets.sort = false;">
            <div slot="header">
                {{ $t('Sort By') }}
            </div>

            <div slot="content">
                <ul>
                    <li v-for="option in sortOptions">
                        <span class="radio" :class="option.code">
                            <input
                                type="radio"
                                :id="option.code"
                                name="sort"
                                v-bind:value="option.code"
                                v-model="appliedFilters.sort"
                                @change="sort()">

                            <label class="radio-view" :for="sort.code"></label>

                            {{ option.label }}
                        </span>
                    </li>
                </ul>
            </div>
        </bottom-sheet>

        <bottom-sheet :show="bottomSheets.filter" :class-name="'filter-bottom-sheet'" @onBottomSheetClose="bottomSheets.filter = false;">
            <div slot="header">
                {{ $t('Filters') }}

                <span class="label label-grey" @click="clearFilter">{{ $t('Clear All') }}</span>
            </div>

            <div slot="content">
                <div class="filter-attributes">
                    <div class="filter-item" v-for="attribute in attributes">

                        <div class="filter-title">
                            {{ attribute.name }}
                        </div>

                        <div class="filter-content" :class="[attribute.swatch_type, attribute.type]">
                            <ol v-if="attribute.type == 'select' && attribute.swatch_type != 'color'">
                                <li v-for="option in attribute.options">
                                    <label :for="attribute.code + '_' + option.id"></label>

                                    <input type="checkbox"
                                        :id="attribute.code + '_' + option.id"
                                        v-bind:value="option.id"
                                        v-model="appliedFilters[attribute.code]"/>

                                    {{ option.label }}

                                    <i class="icon sharp-done-green"></i>
                                </li>
                            </ol>

                            <div class="swatch-container" v-if="attribute.type == 'select' && attribute.swatch_type == 'color'">
                                <label
                                    class="swatch"
                                    v-for="option in attribute.options"
                                    :for="attribute.code + '_' + option.id"
                                    :style="{ background: option.swatch_value }">
                                    <input type="checkbox"
                                        :id="attribute.code + '_' + option.id"
                                        v-bind:value="option.id"
                                        v-model="appliedFilters[attribute.code]"/>

                                    <i class="icon sharp-done-white"></i>
                                </label>
                            </div>

                            <div class="price-slider" v-if="attribute.type == 'price'">
                                <div class="price-range">
                                    <span class="min-price">{{ appliedFilters[attribute.code][0] | currency }}</span>

                                    <span class="max-price">{{ appliedFilters[attribute.code][1] | currency }}</span>
                                </div>

                                <vue-slider
                                    ref="slider"
                                    v-model="appliedFilters[attribute.code]"
                                    v-bind="sliderConfig"
                                    @callback="priceRangeUpdated(attribute.code, $event)"
                                    @onCallback="priceRangeUpdated(attribute.code, $event)"
                                ></vue-slider>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="filter-action">
                    <span class="cancel-btn" @click="cancelFilter">{{ $t('Cancel') }}</span>

                    <button class="btn btn-black" @click="applyFilter">{{ $t('Apply') }}</button>
                </div>
            </div>
        </bottom-sheet>
    </div>
</template>

<script>
    import BottomSheet from '../shared/bottom-sheet';
    import VueSlider   from 'vue-slider-component';
    import 'vue-slider-component/theme/antd.css';

    export default {
        name: 'layered-navigation',
        
        components: { BottomSheet, VueSlider },

        data () {
			return {
                bottomSheets: {
                    sort: false,
                    filter: false
                },

                sortOptions: [
                    {
                        'code': 'name_asc',
                        'label': this.$t('From A-Z'),
                        'sort': 'name',
                        'order': 'asc'
                    }, {
                        'code': 'name_desc',
                        'label': this.$t('From Z-A'),
                        'sort': 'name',
                        'order': 'desc'
                    }, {
                        'code': 'created_at_desc',
                        'label': this.$t('Newest First'),
                        'sort': 'created_at',
                        'order': 'desc'
                    }, {
                        'code': 'created_at_asc',
                        'label': this.$t('Oldest First'),
                        'sort': 'created_at',
                        'order': 'asc'
                    }, {
                        'code': 'price_asc',
                        'label': this.$t('Cheapest First'),
                        'sort': 'price',
                        'order': 'asc'
                    }, {
                        'code': 'price_desc',
                        'label': this.$t('Expensive First'),
                        'sort': 'price',
                        'order': 'desc'
                    }
                ],

                attributes: [],

                appliedFilters: {},

                sliderConfig: {
                    lazy: true,
                    max: 5000,
                    dotSize: 10,
                    height: 2,
                    processStyle: {
                        'backgroundColor': '#616161'
                    },
                    tooltipStyle: {
                        'display': 'none'
                    }
                }
			}
		},

        mounted () {
            this.getFilerableAttributes();
        },

        watch: {
            '$route.params.id': function (id) {
                this.clearFilter()
            }
        },

        methods: {
            getFilerableAttributes () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get('/api/attributes', { params: { is_filterable: 1, pagination: 0 } })
                    .then(function(response) {
                        this_this.attributes = response.data.data;

                        EventBus.$emit('hide-ajax-loader');

                        this_this.attributes.forEach(function(attribute) {
                            this_this.$set(this_this.appliedFilters, attribute.code, attribute.type == 'price' ? [0, 0] : [])
                        })
                    })
                    .catch(function (error) {});
            },

            cancelFilter () {
                this.bottomSheets.filter = false;

                this.clearFilter();
            },

            clearFilter () {
                for (var key in this.appliedFilters) {
                    this.$set(this.appliedFilters, key, key == 'price' ? [0, 0] : [])
                }
            },

            applyFilter () {
                this.bottomSheets.filter = false;

                this.$emit('onFilterApplied', this.appliedFilters)
            },

            sort () {
                this.bottomSheets.sort = false;

                this.$emit('onFilterApplied', this.appliedFilters)
            }
        }
    }
</script>

<style lang="scss">
    .layered-filter-wrapper {
        height: 78px;
        width: 100%;
        z-index: 10;

        .filter {
            z-index: 10;
            background: #FFFFFF;
            box-shadow: 0 10px 10px 0 rgba(0, 0, 0, 0.04), 0 1px 4px 0 rgba(0, 0, 0, 0.16);
            width: 100%;
            height: 56px;

            &.fixed-header {
                position: fixed;
                top: 0;
                left: 0;
            }

            ul {
                li {
                    // width: 33.33%;
                    width: 50%;
                    float: left;
                    padding: 16px;
                    font-size: 14px;
                    color: rgba(0, 0, 0, 0.86);
                    font-weight: 600;
                    text-transform: uppercase;
                    cursor: pointer;

                    .icon {
                        vertical-align: middle;
                        margin-right: 8px;
                    }
                }
            }
        }
    }

    .filter-bottom-sheet {
        .bottom-sheet-content {
            overflow-y: auto;
            padding-bottom: 68px;
            background: #F5F5F5;
        }
    }

    .filter-bottom-sheet {
        .bottom-sheet-header {
            .label {
                float: right;
            }
        }
    }

    .filter-attributes {
        background: #F5F5F5;

        .filter-item {
            margin-bottom: 12px;
            background: #ffffff;

            &:last-child {
                margin-bottom: 0;
            }

            .filter-title {
                padding: 16px;
                font-size: 12px;
                text-transform: uppercase;
                color: rgba(0, 0, 0, 0.6);
                border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            }

            &:first-child {
                .filter-title {
                    border-top: 1px solid rgba(0, 0, 0, 0.08);
                }
            }

            .filter-content {
                width: 100%;
                padding-left: 16px;
                display: inline-block;

                &.color {
                    padding: 5px;
                }

                &.price {
                    padding: 16px;
                }

                ol {
                    padding: 0;
                    list-style: none;

                    li {
                        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
                        padding-left: 0px !important;
                        position: relative;
                        font-size: 14px;
                        font-weight: 500;

                        label {
                            position: absolute;
                            width: 100%;
                            height: 100%;
                            top: 0;
                        }
                        
                        input {
                            display: none;
                        }

                        &:last-child {
                            border-bottom: 0;
                        }

                        .icon.sharp-done-green {
                            float: right;
                            display: none;
                        } 

                        input:checked + .icon.sharp-done-green {
                            display: inline-block;
                        }
                    }
                }

                .swatch-container {
                    .swatch {
                        width: 48px;
                        height: 48px;
                        float: left;
                        padding: 11px;
                        margin: 10px;

                        input {
                            display: none;
                        }

                        .icon.sharp-done-white {
                            display: none;
                        } 

                        input:checked + .icon.sharp-done-white {
                            display: inline-block;
                        }
                    }
                }

                .price-slider {
                    .price-range {
                        width: 100%;
                        float: left;
                        margin-bottom: 16px;

                        .min-price {
                            float: left;
                        }

                        .max-price {
                            float: right;
                        }
                    }
                }
            }
        }
    }

    .filter-action {
        position: fixed;
        bottom: 0;
        padding: 6px 8px;
        width: 100%;
        background: #ffffff;
        box-shadow: 0 -10px 10px 0 rgba(0, 0, 0, 0.04), 0 -1px 4px 0 rgba(0, 0, 0, 0.16);

        .cancel-btn {
            font-size: 14px;
            color: rgba(0,0,0,0.56);
            padding: 13px 50px;
            text-transform: uppercase;
            float: left;
        }

        .btn {
            float: right;
        }
    }
</style>