import Vue               from 'vue';
import Toasted           from 'vue-toasted';
import router            from './router';
import App               from './components/app';
import VueCurrencyFilter from 'vue-currency-filter'
import i18n              from './plugins/i18n';
import './plugins/push-notification';
// import './../../../../../../../public/firebase-messaging-sw';     // (File from the public folder)
// import './../../../../publishable/pwa/firebase-messaging-sw';    //  (File from the pwa foder where main service-worker is kept)

window.jQuery = window.$ = require('jquery');
window.axios = require('axios');
window.VeeValidate = require('vee-validate');
window.EventBus = new Vue();
Vue.prototype.$http = axios;

axios.defaults.baseURL = window.config.app_base_url;

require('./bootstrap');

Vue.use(VueCurrencyFilter, {
    symbol : '$',
    thousandsSeparator: ',',
    fractionCount: 2,
    fractionSeparator: '.',
    symbolPosition: 'front',
    symbolSpacing: false
});

Vue.use(VeeValidate);

Vue.use(Toasted, {
    fullWidth: true,
    fitToScreen: true,
    closeOnSwipe: false,
    duration: 1000
});

Vue.use(require('vue-moment'));

Vue.directive("sticky", require("./directives/sticky"));

const app = new Vue({
    el: '#app',

    components: { App },

    i18n,

    created () {
        var this_this = this;

        axios.interceptors.response.use(
            function(response) {
                if (response.data.offline) {
                    this_this.$router.push({name: 'offline'})
                } else {
                    return response;
                }
            },
            this.errorResponseHandler
        );
    },

    methods: {
        errorResponseHandler (error) {
            EventBus.$emit('destroy-ajax-loader');

            if (error.response.status == 401) {
                if (this.$route.fullPath.includes('/account/')) {
                    localStorage.removeItem('currentUser');

                    EventBus.$emit('user-logged-out');

                    this.$toasted.show(error.response.data.error, { type: 'error' })

                    this.$router.push({name: 'login-register'})
                } else {
                    return Promise.reject(error);
                }
            } else {
                this.$toasted.show(error.response.data.error, { type: 'error' })

                return Promise.reject(error);
            }
        }
    },

    router
});
