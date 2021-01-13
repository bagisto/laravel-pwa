 <template>
    
    <div
        v-if="
            is_facebook
            || is_twitter
            || is_google
            || is_linkedin
            || is_github
        "
        class="social-login-links"
        >
        <div class="control-group" v-if="is_facebook">
            <a :href="`${app_base_url}/customer/social-login/facebook`" class="link facebook-link">
                <span class="icon icon-facebook-login"></span>
                {{ $t('social_login.continue-with-facebook') }}
            </a>
        </div>

        <div class="control-group" v-if="is_twitter">
            <a :href="`${app_base_url}/customer/social-login/twitter`" class="link twitter-link">
                <span class="icon icon-twitter-login"></span>
                {{ $t('social_login.continue-with-twitter') }}
            </a>
        </div>

        <div class="control-group" v-if="is_google">
            <a :href="`${app_base_url}/customer/social-login/google`" class="link google-link">
                <span class="icon icon-google-login"></span>
                {{ $t('social_login.continue-with-google') }}
            </a>
        </div>

        <div class="control-group" v-if="is_linkedin">
            <a :href="`${app_base_url}/customer/social-login/linkedin`" class="link linkedin-link">
                <span class="icon icon-linkedin-login"></span>
                {{ $t('social_login.continue-with-linkedin') }}
            </a>
        </div>

        <div class="control-group" v-if="is_github">
            <a :href="`${app_base_url}/customer/social-login/github`" class="link github-link">
                <span class="icon icon-github-login"></span>
                {{ $t('social_login.continue-with-github') }}
            </a>
        </div>

        <div class="social-link-seperator">
            <span>{{ $t('social_login.or') }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'social-login',

        data: function () {
            return {
                is_facebook : false,
                is_twitter  : false,
                is_google   : false,
                is_linkedin : false,
                is_github   : false,
                app_base_url: window.config.app_base_url
            }
        },

        mounted () {
            this.getConfigData();
        },

        methods: {
            getConfigData: function () {
                EventBus.$emit('show-ajax-loader');

                var enable_facebook_key = 'customer.settings.social_login.enable_facebook';
                var enable_twitter_key = 'customer.settings.social_login.enable_twitter';
                var enable_google_key = 'customer.settings.social_login.enable_google';
                var enable_linkedin_key = 'customer.settings.social_login.enable_linkedin';
                var enable_github_key = 'customer.settings.social_login.enable_github';

                this.$http.get("/api/config", {
                    params: {
                        _config: `${enable_facebook_key},${enable_twitter_key},${enable_google_key},${enable_linkedin_key},${enable_github_key}`
                    }
                }).then(response => {
                    EventBus.$emit('hide-ajax-loader');

                    this.is_facebook = (response.data.data[enable_facebook_key] == "1");
                    this.is_twitter = (response.data.data[enable_twitter_key] == "1");
                    this.is_google = (response.data.data[enable_google_key] == "1");
                    this.is_linkedin = (response.data.data[enable_linkedin_key] == "1");
                    this.is_github = (response.data.data[enable_github_key] == "1");
                })
                .catch(function (error) {});
            },
        }
    }
</script>

