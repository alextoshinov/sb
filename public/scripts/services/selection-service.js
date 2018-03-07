"use strict";

angular.module("showyBulgariaApp").
	factory("selection", ["$window", function($window) {

		var SelectionService = function() {
		};

		SelectionService.prototype.clear = function(query) {
			if ($window.getSelection) {
				if ($window.getSelection().empty) {
					$window.getSelection().empty();
				} else if ($window.getSelection().removeAllRanges) {
					$window.getSelection().removeAllRanges();
				}
			} else if ($window.document.selection) {
				$window.document.selection.empty();
			}
			$window.document.activeElement.blur();
		};

		return new SelectionService();
	}]);
