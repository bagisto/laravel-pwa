"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var getCustomer = function getCustomer(_ref) {
  var commit = _ref.commit;
  commit('GET_CUSTOMER');
};

var _default = {
  getCustomer: getCustomer
};
exports["default"] = _default;