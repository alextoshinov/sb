"use strict";
angular.module("showyBulgariaApp").controller("AdminController",
	["$http", "$scope", function($http, $scope) {

	$scope.reviews = [];
	$http({method: "GET", url: "/api/v1/reviews?status=unreviewed"}).
		success(function(data, status, headers, config) {
			$scope.reviews = data;
		});

	$scope.accept = function(review) {
		$http({method: "GET", url: "/api/v1/reviews?string_id=" + review.string_id + "&action=accept"}).
			success(function(data, status, headers, config) {
				$scope.reviews.splice($scope.reviews.indexOf(review), 1);
			});
	};

	$scope.reject = function(review) {
		$http({method: "GET", url: "/api/v1/reviews?string_id=" + review.string_id + "&action=reject"}).
			success(function(data, status, headers, config) {
				$scope.reviews.splice($scope.reviews.indexOf(review), 1);
			});
	};
}]);