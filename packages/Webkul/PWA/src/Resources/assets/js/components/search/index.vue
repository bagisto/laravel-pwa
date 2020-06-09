<template>
    <div class="content">
        <custom-header>
            <div slot="content">
                <form @submit.prevent="search(term)">
                    <input type="text" class="search-control" v-model="term" :placeholder="$t('Search for products')"/>
                </form>
            </div>
        </custom-header>

        <div class="search-container">
            <div class="panel recent-searches" v-if="recentSearches.length">
                <div class="panel-heading">
                    {{ $t('Recent Searches') }}

                    <span class="label label-grey" @click="removeAllSaveTerm">{{ $t('Clear All') }}</span>
                </div>

                <div class="panel-content">
                    <ol class="search-list">
                        <li v-for="(recentSearch, index) in recentSearches" @click="search(recentSearch)">
                            <span>{{ recentSearch }}</span>

                            <i class="icon sharp-remove-icon" @click="removeSaveTerm(index, $event)"></i>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="panel categories" v-if="categories.length">
                <div class="panel-heading">
                    {{ $t('Categories') }}
                </div>

                <div class="panel-content">
                    <ul class="category-list">
                        <li v-for="category in categories">
                            <router-link :to="''">
                                <span>{{ category.name }}</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomHeader from '../layouts/custom-header';

    export default {
        name: 'search',

        components: { CustomHeader },

        data () {
            return {
                term: '',

                recentSearches: [],

                categories: []
            }
        },

        mounted () {
            var terms = localStorage.getItem('recent-terms');

            this.recentSearches = terms ? JSON.parse(terms) : [];

            this.getCategories(); 
        },

        methods: {
            getCategories () {
                var this_this = this;

                EventBus.$emit('show-ajax-loader');

                this.$http.get("/api/descendant-categories", { params: { parent_id: window.channel.root_category_id } })
                    .then(function(response) {
                        EventBus.$emit('hide-ajax-loader');

                        this_this.categories = response.data.data;
                    })
                    .catch(function (error) {});
            },
            
            search (term) {
                if (term == '')
                    return;

                if (! this.recentSearches.includes(term)) {
                    this.recentSearches.unshift(term);

                    localStorage.setItem('recent-terms', JSON.stringify(this.recentSearches));
                }

                this.$router.push({ path: '/search/' + term })
            },
        
            removeSaveTerm (index, event) {
                event.stopPropagation();

                this.recentSearches.splice(index, 1);

                localStorage.setItem('recent-terms', JSON.stringify(this.recentSearches));
            },

            removeAllSaveTerm () {
                this.recentSearches = [];

                localStorage.removeItem('recent-terms');
            }
        }
    }
</script>

<style scoped lang="scss">
    .content {
        position: absolute;
        bottom: 0;
        top: 0;
        left: 0;
        width: 100%;

        .search-control {
            padding: 2px 0;
            border: none;
            font-size: 16px;
        }

        .search-container {
            margin-top: 66px;

            .panel {
                border-bottom: 1px solid rgba(0, 0, 0 ,0.12);
                margin-bottom: 12px;

                .panel-heading {
                    .label-grey {
                        float: right;
                        padding: 2px 5px;
                        font-size: 10px;
                        cursor: pointer;
                    }
                }

                .panel-content {
                    ol.search-list {
                        padding: 0;
                        list-style: none;

                        li {
                            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
                            padding: 16px 16px 16px 0;
                            position: relative;
                            font-size: 14px;
                            font-weight: 500;

                            &:last-child {
                                border-bottom: 0;
                            }

                            .sharp-remove-icon {
                                cursor: pointer;
                                float: right;
                                opacity: 0.56;
                                margin-top: -3px;
                            }
                        }
                    }

                    ul.category-list {
                        -webkit-overflow-scrolling: touch;
                        -overflow-scrolling: touch;
                        overflow-x: auto;
                        white-space: nowrap;
                        padding: 16px;
                        background: #fff;

                        li {
                            display: inline-block;
                            margin-right: 8px;
                            position: relative;

                            &:last-child {
                                margin-right: 0;
                            }

                            a {
                                padding: 8px 16px;
                                font-size: 14px;
                                font-weight: 600;
                                background: #000000;
                                color: #ffffff;
                                border-radius: 18px;
                                display: block;
                            }
                        }
                    }
                }

                &.recent-searches {
                    .panel-content {
                        padding: 0 0 0 16px;
                    }
                }

                &.categories {
                    margin-top: 0;

                    .panel-content {
                        padding: 0;
                    }
                }
            }
        }
    }

    ::-webkit-input-placeholder {
        color: rgba(0, 0, 0, 0.5);
    }

    :-ms-input-placeholder {
        color: rgba(0, 0, 0, 0.5);
    }

    ::placeholder {
        color: rgba(0, 0, 0, 0.5);
    }
</style>