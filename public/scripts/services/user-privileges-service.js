"use strict";

angular.module("showyBulgariaApp").
	factory("userPrivileges", ["$window", function($window) {
		var UserPrivilegesService = function() {
		};

		UserPrivilegesService.prototype.canSetHikeIsFeatured = function() {
			return $window.showybulgaria.userPrivileges.set_hike_is_featured;
		};

		return new UserPrivilegesService();
	}]);
