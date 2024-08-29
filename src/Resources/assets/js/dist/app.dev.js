"use strict";

var _vue = _interopRequireDefault(require("vue"));

var _router = _interopRequireDefault(require("./router"));

var _store = _interopRequireDefault(require("./store"));

var _vueToasted = _interopRequireDefault(require("vue-toasted"));

var _i18n = _interopRequireDefault(require("./plugins/i18n"));

var _app = _interopRequireDefault(require("./components/app"));

var _vueCurrencyFilter = _interopRequireDefault(require("vue-currency-filter"));

require("./plugins/push-notification");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

// import './../../../../../../../public/firebase-messaging-sw';     // (File from the public folder)
// import './../../../../publishable/pwa/firebase-messaging-sw';    //  (File from the pwa foder where main service-worker is kept)
window.axios = require('axios');
window.jQuery = window.$ = require('jquery');
window.VeeValidate = require('vee-validate');
window.EventBus = new _vue["default"]();
_vue["default"].prototype.$http = axios;
axios.defaults.baseURL = window.config.app_base_url;

require('./bootstrap');

_vue["default"].use(_vueCurrencyFilter["default"], {
  symbol: window.config.currentCurrency ? window.config.currentCurrency.symbol : '$',
  thousandsSeparator: ',',
  fractionCount: 2,
  fractionSeparator: '.',
  symbolPosition: 'front',
  symbolSpacing: false
});

_vue["default"].use(VeeValidate);

_vue["default"].use(_vueToasted["default"], {
  fullWidth: true,
  fitToScreen: true,
  closeOnSwipe: true,
  duration: 2000,
  // you can pass a single action as below
  action: {
    text: 'X',
    onClick: function onClick(e, toastObject) {
      toastObject.goAway(0);
    }
  }
});

_vue["default"].use(require('vue-moment'));

_vue["default"].directive("sticky", require("./directives/sticky"));

var app = new _vue["default"]({
  el: '#app',
  components: {
    App: _app["default"]
  },
  i18n: _i18n["default"],
  created: function created() {
    var this_this = this;
    axios.interceptors.response.use(function (response) {
      if (response.data.offline) {
        this_this.$router.push({
          name: 'offline'
        });
      } else {
        return response;
      }
    }, this.errorResponseHandler);
  },
  methods: {
    errorResponseHandler: function errorResponseHandler(error) {
      EventBus.$emit('destroy-ajax-loader');

      switch (error.response.status) {
        case 401:
          if (this.$route.fullPath.includes('/account/')) {
            localStorage.removeItem('currentUser');
            EventBus.$emit('user-logged-out');
            this.$toasted.show(this.$t('please_login_first'), {
              type: 'error'
            });
            this.$router.push({
              name: 'login-register'
            });
          } else {
            localStorage.removeItem('currentUser');
            return Promise.reject(error);
          }

          break;

        default:
          this.$toasted.show(error.response.data.error, {
            type: 'error'
          });
          return Promise.reject(error);
          break;
      }
    }
  },
  router: _router["default"],
  store: _store["default"]
});