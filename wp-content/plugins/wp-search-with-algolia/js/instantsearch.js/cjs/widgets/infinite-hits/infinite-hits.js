"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.default = void 0;

var _preact = require("preact");

var _uiComponentsShared = require("@algolia/ui-components-shared");

var _InfiniteHits = _interopRequireDefault(require("../../components/InfiniteHits/InfiniteHits.js"));

var _connectInfiniteHits = _interopRequireDefault(require("../../connectors/infinite-hits/connectInfiniteHits.js"));

var _index = require("../../lib/utils/index.js");

var _index2 = require("../../lib/templating/index.js");

var _suit = require("../../lib/suit.js");

var _index3 = require("../../lib/insights/index.js");

var _defaultTemplates = _interopRequireDefault(require("./defaultTemplates.js"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var withUsage = (0, _index.createDocumentationMessageGenerator)({
  name: 'infinite-hits'
});
var suit = (0, _suit.component)('InfiniteHits');
var InfiniteHitsWithInsightsListener = (0, _index3.withInsightsListener)(_InfiniteHits.default);

var renderer = function renderer(_ref) {
  var containerNode = _ref.containerNode,
      cssClasses = _ref.cssClasses,
      renderState = _ref.renderState,
      templates = _ref.templates,
      hasShowPrevious = _ref.showPrevious;
  return function (_ref2, isFirstRendering) {
    var hits = _ref2.hits,
        results = _ref2.results,
        showMore = _ref2.showMore,
        showPrevious = _ref2.showPrevious,
        isFirstPage = _ref2.isFirstPage,
        isLastPage = _ref2.isLastPage,
        instantSearchInstance = _ref2.instantSearchInstance,
        insights = _ref2.insights,
        bindEvent = _ref2.bindEvent;

    if (isFirstRendering) {
      renderState.templateProps = (0, _index2.prepareTemplateProps)({
        defaultTemplates: _defaultTemplates.default,
        templatesConfig: instantSearchInstance.templatesConfig,
        templates: templates
      });
      return;
    }

    (0, _preact.render)((0, _preact.h)(InfiniteHitsWithInsightsListener, {
      cssClasses: cssClasses,
      hits: hits,
      results: results,
      hasShowPrevious: hasShowPrevious,
      showPrevious: showPrevious,
      showMore: showMore,
      templateProps: renderState.templateProps,
      isFirstPage: isFirstPage,
      isLastPage: isLastPage,
      insights: insights,
      sendEvent: function sendEvent(event) {
        instantSearchInstance.sendEventToInsights(event);
      },
      bindEvent: bindEvent
    }), containerNode);
  };
};

var infiniteHits = function infiniteHits(widgetParams) {
  var _ref3 = widgetParams || {},
      container = _ref3.container,
      escapeHTML = _ref3.escapeHTML,
      transformItems = _ref3.transformItems,
      _ref3$templates = _ref3.templates,
      templates = _ref3$templates === void 0 ? {} : _ref3$templates,
      _ref3$cssClasses = _ref3.cssClasses,
      userCssClasses = _ref3$cssClasses === void 0 ? {} : _ref3$cssClasses,
      showPrevious = _ref3.showPrevious,
      cache = _ref3.cache;

  if (!container) {
    throw new Error(withUsage('The `container` option is required.'));
  }

  var containerNode = (0, _index.getContainerNode)(container);
  var cssClasses = {
    root: (0, _uiComponentsShared.cx)(suit(), userCssClasses.root),
    emptyRoot: (0, _uiComponentsShared.cx)(suit({
      modifierName: 'empty'
    }), userCssClasses.emptyRoot),
    item: (0, _uiComponentsShared.cx)(suit({
      descendantName: 'item'
    }), userCssClasses.item),
    list: (0, _uiComponentsShared.cx)(suit({
      descendantName: 'list'
    }), userCssClasses.list),
    loadPrevious: (0, _uiComponentsShared.cx)(suit({
      descendantName: 'loadPrevious'
    }), userCssClasses.loadPrevious),
    disabledLoadPrevious: (0, _uiComponentsShared.cx)(suit({
      descendantName: 'loadPrevious',
      modifierName: 'disabled'
    }), userCssClasses.disabledLoadPrevious),
    loadMore: (0, _uiComponentsShared.cx)(suit({
      descendantName: 'loadMore'
    }), userCssClasses.loadMore),
    disabledLoadMore: (0, _uiComponentsShared.cx)(suit({
      descendantName: 'loadMore',
      modifierName: 'disabled'
    }), userCssClasses.disabledLoadMore)
  };
  var specializedRenderer = renderer({
    containerNode: containerNode,
    cssClasses: cssClasses,
    templates: templates,
    showPrevious: showPrevious,
    renderState: {}
  });
  var makeWidget = (0, _index3.withInsights)(_connectInfiniteHits.default)(specializedRenderer, function () {
    return (0, _preact.render)(null, containerNode);
  });
  return _objectSpread(_objectSpread({}, makeWidget({
    escapeHTML: escapeHTML,
    transformItems: transformItems,
    showPrevious: showPrevious,
    cache: cache
  })), {}, {
    $$widgetType: 'ais.infiniteHits'
  });
};

var _default = infiniteHits;
exports.default = _default;