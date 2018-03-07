"use strict";

angular.module("showyBulgariaApp").
	directive("mailto", function() {
		return {
			link: function (scope, element, attrs) {
				element.attr("href", "mailto:info@showybulgaria.com");
			}
		};
	});