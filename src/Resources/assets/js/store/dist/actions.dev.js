"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var getCustomer = function getCustomer(_ref) {
  var commit = _ref.commit;
  commit('GET_CUSTOMER');
};

var getCart = function getCart(_ref2) {
  var commit = _ref2.commit;
  commit('GET_CART');
};

var _default = {
  getCart: getCart,
  getCustomer: getCustomer
};
exports["default"] = _default;