"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var state = {
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
}, 0);
var _default = state;
exports["default"] = _default;