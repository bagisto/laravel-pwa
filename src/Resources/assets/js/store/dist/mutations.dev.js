"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var _vue = _interopRequireDefault(require("vue"));

var _router = _interopRequireDefault(require("../router"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var setCustomer = function setCustomer(state, customer) {
  EventBus.$emit('hide-ajax-loader');
  state.customer = customer;
  state.isCustomerFetched = true;

  if (_router["default"].app._route.name == 'login-register') {
    _router["default"].app._router.push({
      name: 'dashboard'
    });
  }
};

var GET_CUSTOMER = function GET_CUSTOMER(state) {
  EventBus.$emit('show-ajax-loader');

  if (!state.isCustomerFetched) {
    _vue["default"].prototype.$http.get('/api/customer/get').then(function (response) {
      setCustomer(state, response.data.data);
    })["catch"](function (error) {
      state.isCustomerFetched = true;
    });
  }

  if (state.customer) {
    setCustomer(state, state.customer);
  }

  EventBus.$emit('hide-ajax-loader');
};

var GET_CART = function GET_CART(state) {
  EventBus.$emit('show-ajax-loader');

  _vue["default"].prototype.$http.get('/api/pwa/checkout/cart').then(function (response) {
    EventBus.$emit('hide-ajax-loader');
    state.cart = response.data.data;
    state.pagination = response.data.meta;
  })["catch"](function (error) {});
};

var _default = {
  GET_CART: GET_CART,
  GET_CUSTOMER: GET_CUSTOMER
};
exports["default"] = _default;