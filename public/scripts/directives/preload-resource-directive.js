"use strict";

angular.module("showyBulgariaApp").
	directive("preloadResource", ["resourceCache", function(resourceCache) {
		return {
			link: function (scope, element, attrs) {
				// # HACKY, browser is doing some processing on the html before I can get to it, 
				// even though it is properly escaped on the backend.
				var unescaped = element.html().replace(/"\\&quot;|\\&quot;"|'\\"/g, "\\\"").replace(/&amp;/g, "&");
				resourceCache.put(attrs.preloadResource, unescaped);
			}
		};
	}]);