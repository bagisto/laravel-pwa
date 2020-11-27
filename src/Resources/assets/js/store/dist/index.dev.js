"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

var _vue = _interopRequireDefault(require("vue"));

var _vuex = _interopRequireDefault(require("vuex"));

var _state = _interopRequireDefault(require("./state"));

var _actions = _interopRequireDefault(require("./actions"));

var _mutations = _interopRequireDefault(require("./mutations"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

_vue["default"].use(_vuex["default"]);

var store = new _vuex["default"].Store({
  state: _state["default"],
  actions: _actions["default"],
  mutations: _mutations["default"]
});
var _default = store;
exports["default"] = _default;