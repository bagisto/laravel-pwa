"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var state = {
  cart: null,
  pagination: null,
  customer: false,
  isCustomerFetched: false
};
setTimeout(function () {
  EventBus.$on('user-logged-in', function (user) {
    state.customer = user;
  });
  EventBus.$on('user-logged-out', function () {
    state.customer = null;
  });
  EventBus.$on('checkout.cart.changed', function (cart) {
    state.cart = cart;
  });
}, 0);
var _default = state;
exports["default"] = _default;