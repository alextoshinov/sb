"use strict";

angular.module("showyBulgariaApp").
	factory("filterParser", ["$filter", function($filter) {

		var FilterParser = function() {
		};

		FilterParser.prototype.filter = function(filterStr, value) {
			var filterParams = filterStr.split(":");
			var filterName = filterParams.splice(0, 1);
			filterParams.splice(0, 0, value);
			return $filter(filterName).apply(this, filterParams);
		};

		return new FilterParser();
	}]);
