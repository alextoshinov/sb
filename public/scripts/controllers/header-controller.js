"use strict";
angular.module("showyBulgariaApp").controller("HeaderController",
	["$scope", "$window","$location", "navigation", "preferences", "search", "$auth", "toastr",
	function($scope, $window, $location, navigation, preferences, search, $auth, toastr) {

	$scope.searchQuery = "";

	$scope.search = function() {
		$window.document.activeElement.blur();
		if (preferences.searchBy === "location") {
			search.searchByLocation($scope.searchQuery).then(function() {
				$scope.isSearching = false;
			});
		} else {
			search.searchByName($scope.searchQuery).then(function() {
				$scope.isSearching = false;
			});
		}
		$scope.hideSearchBox(); // On the application scope, probably should be an event.
		$scope.searchQuery = "";
	};

	$scope.ignoreClickIfOnMap = function(event) {
		if (navigation.onMap()) {
			event.preventDefault();
			event.stopPropagation();
			return false;
		}
	};

	$scope.ClickonLogin = function(event) {
		if (navigation.onLogin()) {
			event.preventDefault();
			event.stopPropagation();
			return false;
		}
	};

	$scope.logout = function () {   
        $auth.logout().then(function() {
            toastr.info('You have been logged out');
            $window.localStorage.removeItem('currentUser');
            $location.path('/');
        });
    }; 

	$scope.ClickonSignup = function(event) {
		if (navigation.onSignup()) {
			event.preventDefault();
			event.stopPropagation();
			return false;
		}
	};

	$scope.isAuthenticated = function() {
      return $auth.isAuthenticated();
    };

	console.log('isAuthenticated: ',$auth.isAuthenticated());

}]);