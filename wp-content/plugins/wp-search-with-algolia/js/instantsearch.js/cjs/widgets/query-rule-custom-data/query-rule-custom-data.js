"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = exports.defaultTemplates = void 0;

var _preact = require("preact");

var _uiComponentsShared = require("@algolia/ui-components-shared");

var _index = require("../../lib/utils/index.js");

var _suit = require("../../lib/suit.js");

var _connectQueryRules = _interopRequireDefault(require("../../connectors/query-rules/connectQueryRules.js"));

var _QueryRuleCustomData = _interopRequireDefault(require("../../components/QueryRuleCustomData/QueryRuleCustomData.js"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var defaultTemplates = {
  default: function _default(_ref) {
    var items = _ref.items;
    return JSON.stringify(items, null, 2);
  }
};
exports.defaultTemplates = defaultTemplates;
var withUsage = (0, _index.createDocumentationMessageGenerator)({
  name: 'query-rule-custom-data'
});
var suit = (0, _suit.component)('QueryRuleCustomData');

var renderer = function renderer(_ref2) {
  var containerNode = _ref2.containerNode,
      cssClasses = _ref2.cssClasses,
      templates = _ref2.templates;
  return function (_ref3) {
    var items = _ref3.items;
    (0, _preact.render)((0, _preact.h)(_QueryRuleCustomData.default, {
      cssClasses: cssClasses,
      templates: templates,
      items: items
    }), containerNode);
  };
};

var queryRuleCustomData = function queryRuleCustomData(widgetParams) {
  var _ref4 = widgetParams || {},
      container = _ref4.container,
      _ref4$cssClasses = _ref4.cssClasses,
      userCssClasses = _ref4$cssClasses === void 0 ? {} : _ref4$cssClasses,
      _ref4$templates = _ref4.templates,
      userTemplates = _ref4$templates === void 0 ? {} : _ref4$templates,
      _ref4$transformItems = _ref4.transformItems,
      transformItems = _ref4$transformItems === void 0 ? function (items) {
    return items;
  } : _ref4$transformItems;

  if (!container) {
    throw new Error(withUsage('The `container` option is required.'));
  }

  var cssClasses = {
    root: (0, _uiComponentsShared.cx)(suit(), userCssClasses.root)
  };
  var containerNode = (0, _index.getContainerNode)(container);

  var templates = _objectSpread(_objectSpread({}, defaultTemplates), userTemplates);

  var specializedRenderer = renderer({
    containerNode: containerNode,
    cssClasses: cssClasses,
    renderState: {},
    templates: templates
  });
  var makeWidget = (0, _connectQueryRules.default)(specializedRenderer, function () {
    (0, _preact.render)(null, containerNode);
  });
  return _objectSpread(_objectSpread({}, makeWidget({
    transformItems: transformItems
  })), {}, {
    $$widgetType: 'ais.queryRuleCustomData'
  });
};

var _default2 = queryRuleCustomData;
exports.default = _default2;