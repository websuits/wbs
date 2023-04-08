function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

import { h, render } from 'preact';
import { cx } from '@algolia/ui-components-shared';
import { getContainerNode, createDocumentationMessageGenerator } from "../../lib/utils/index.js";
import { component } from "../../lib/suit.js";
import connectVoiceSearch from "../../connectors/voice-search/connectVoiceSearch.js";
import VoiceSearchComponent from "../../components/VoiceSearch/VoiceSearch.js";
import defaultTemplates from "./defaultTemplates.js";
var withUsage = createDocumentationMessageGenerator({
  name: 'voice-search'
});
var suit = component('VoiceSearch');

var renderer = function renderer(_ref) {
  var containerNode = _ref.containerNode,
      cssClasses = _ref.cssClasses,
      templates = _ref.templates;
  return function (_ref2) {
    var isBrowserSupported = _ref2.isBrowserSupported,
        isListening = _ref2.isListening,
        toggleListening = _ref2.toggleListening,
        voiceListeningState = _ref2.voiceListeningState;
    render(h(VoiceSearchComponent, {
      cssClasses: cssClasses,
      templates: templates,
      isBrowserSupported: isBrowserSupported,
      isListening: isListening,
      toggleListening: toggleListening,
      voiceListeningState: voiceListeningState
    }), containerNode);
  };
};

var voiceSearch = function voiceSearch(widgetParams) {
  var _ref3 = widgetParams || {},
      container = _ref3.container,
      _ref3$cssClasses = _ref3.cssClasses,
      userCssClasses = _ref3$cssClasses === void 0 ? {} : _ref3$cssClasses,
      _ref3$templates = _ref3.templates,
      userTemplates = _ref3$templates === void 0 ? {} : _ref3$templates,
      _ref3$searchAsYouSpea = _ref3.searchAsYouSpeak,
      searchAsYouSpeak = _ref3$searchAsYouSpea === void 0 ? false : _ref3$searchAsYouSpea,
      language = _ref3.language,
      additionalQueryParameters = _ref3.additionalQueryParameters,
      createVoiceSearchHelper = _ref3.createVoiceSearchHelper;

  if (!container) {
    throw new Error(withUsage('The `container` option is required.'));
  }

  var containerNode = getContainerNode(container);
  var cssClasses = {
    root: cx(suit(), userCssClasses.root),
    button: cx(suit({
      descendantName: 'button'
    }), userCssClasses.button),
    status: cx(suit({
      descendantName: 'status'
    }), userCssClasses.status)
  };

  var templates = _objectSpread(_objectSpread({}, defaultTemplates), userTemplates);

  var specializedRenderer = renderer({
    containerNode: containerNode,
    cssClasses: cssClasses,
    templates: templates
  });
  var makeWidget = connectVoiceSearch(specializedRenderer, function () {
    return render(null, containerNode);
  });
  return _objectSpread(_objectSpread({}, makeWidget({
    container: containerNode,
    cssClasses: cssClasses,
    templates: templates,
    searchAsYouSpeak: searchAsYouSpeak,
    language: language,
    additionalQueryParameters: additionalQueryParameters,
    createVoiceSearchHelper: createVoiceSearchHelper
  })), {}, {
    $$widgetType: 'ais.voiceSearch'
  });
};

export default voiceSearch;