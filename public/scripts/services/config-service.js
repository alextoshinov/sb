"use strict";

angular.module("showyBulgariaApp").
	service("config", ["$location", function($location) {
		this.isProd = $location.host() === "showybulgaria.com" || $location.host() === "www.showybulgaria.com";
		this.hikeImagesPath = this.isProd ? "http://showybulgaria.com/images" : "/hike-images";
		this.landingPageImagesPath = this.isProd ? "http://showybulgaria.com/landing-page-images" : "/landing-page-images";
		this.socketIoPath = this.isProd ? "http://showybulgaria.com" : "http://showybulgaria.dev";
	}]);