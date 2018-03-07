"use strict";

angular.module("showyBulgariaApp").
	directive("metaDescription", ["$rootScope", function($rootScope) {
		return {
			link: function(scope, element, attributes) {
				$rootScope.metaDescription = attributes.metaDescription;
			}
		};
	}]);