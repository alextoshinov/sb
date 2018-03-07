"use strict";

angular.module("showyBulgariaApp").
	directive("resetFormValidation", function() {
	return {
		link: function(scope, element, attributes) {
			scope.$watch(attributes.resetFormValidation, function(value) {
				if (value) {
					element[0].reset();
				}
			});
		}
	};
});