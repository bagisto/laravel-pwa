<template>
    <div class="content">
        <custom-header>
            <div slot="content">
                <form @submit.prevent="search(term)">
                    <input type="text" class="search-control searchinput" v-model="term" :placeholder="$t('Search for products')"/>
                    <span v-if="image_search_status">
                        <label class="image-search-container" :for="'image-search-container-' + _uid">
                            <i class="icon camera-icon"></i>

                            <input type="file" :id="'image-search-container-' + _uid" ref="image_search_input" v-on:change="uploadImage()" style="display:none"/>

                            <img :id="'uploaded-image-url-' +  + _uid" :src="uploaded_image_url" alt="" width="20" height="20" style="display:none"/>
                        </label>
                    </span>
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
                        <li v-for="(recentSearch, index) in recentSearches" @click="search(recentSearch)" :key="index">
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

                categories: [],

                uploaded_image_url: '',

                image_search_status: true
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

                this.$http.get("/api/pwa/categories", { params: { parent_id: window.channel.root_category_id } })
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
            },
            
            uploadImage: function() {
                    var imageInput = this.$refs.image_search_input;

                    if (imageInput.files && imageInput.files[0]) {
                        if (imageInput.files[0].type.includes('image/')) {
                            var self = this;

                            if (imageInput.files[0].size <= 2000000) {

                                EventBus.$emit('show-ajax-loader');

                                var formData = new FormData();

                                formData.append('image', imageInput.files[0]);

                                axios.post("/api/pwa/image-search-upload", formData, {headers: {'Content-Type': 'multipart/form-data'}})
                                    .then(function(response) {
                                        self.uploaded_image_url = response.data;

                                        var net;

                                        async function app() {
                                            var analysedResult = [];

                                            var queryString = '';

                                            net = await mobilenet.load();

                                            const imgElement = document.getElementById('uploaded-image-url-' +  + self._uid);

                                            try {
                                                const result = await net.classify(imgElement);

                                                result.forEach(function(value) {
                                                    queryString = value.className.split(',');

                                                    if (queryString.length > 1) {
                                                        analysedResult = analysedResult.concat(queryString)
                                                    } else {
                                                        analysedResult.push(queryString[0])
                                                    }
                                                });
                                            } catch (error) {
                                                EventBus.$emit('hide-ajax-loader');

                                                self.$toasted.show('No search results found.', { type: 'error' });
                                            };

                                            localStorage.searched_image_url = self.uploaded_image_url;

                                            queryString = localStorage.searched_terms = analysedResult.join('_');

                                            EventBus.$emit('hide-ajax-loader');
                                        
                                            self.$router.push({ path: '/image-search/' + queryString })


                                        }

                                        app();
                                    })
                                    .catch(function(error) {
                                        EventBus.$emit('hide-ajax-loader');

                                        self.$toasted.show('No search results found.', { type: 'error' });
                                    });
                            } else {

                                imageInput.value = '';

                                self.$toasted.show('Oops! Image size must be less than 2mb', { type: 'error' });

                            }
                        } else {
                            imageInput.value = '';

                            self.$toasted.show('Only images (.jpeg, .jpg, .png, ..) are allowed.', { type: 'error' });
                        }
                    }
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


    @media only screen and (max-width: 400px) {
        .searchinput {
            width:130px;
        }
    }
</style>