'use strict';

/**
 * @ngdoc overview
 * @name showyBulgariaApp
 * @description
 * # showyBulgariaApp
 *
 * Main module of the application.
 */
angular
  .module('showyBulgariaApp', [
    'ngAnimate',
    'ngCookies',
    'ngMessages',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch',
    'ui',
    'seo',
    'satellizer',
    'toastr'
  ])
  .config(["$locationProvider", "$routeProvider", "$authProvider", function($locationProvider, $routeProvider, $authProvider) {

    /**
     * Helper auth functions
     */
    var skipIfLoggedIn = ['$q', '$auth', function($q, $auth) {
      var deferred = $q.defer();
      if ($auth.isAuthenticated()) {
        deferred.reject();
      } else {
        deferred.resolve();
      }
      return deferred.promise;
    }];

    var loginRequired = ['$q', '$location', '$auth', function($q, $location, $auth) {
      var deferred = $q.defer();
      if ($auth.isAuthenticated()) {
        deferred.resolve();
      } else {
        $location.path('/login');
      }
      return deferred.promise;
    }];

    $routeProvider.
      when("/", {
        controller: "IndexController",
        templateUrl: "/partials/index.html",
        title: "showybulgaria.com - Find beautiful hikes"
      }).
      when("/login", {
        controller: "LoginController",
        templateUrl: "/partials/login-page.html",
        title: "Log In - showybulgaria.com",
        resolve: {
          skipIfLoggedIn: skipIfLoggedIn
        }
      }).
      when("/signup", {
        controller: "SignupController",
        templateUrl: "/partials/signup.html",
        title: "Signup - showybulgaria.com"
      }).
      when("/profile", {
        controller: "ProfileController",
        templateUrl: "/partials/profile.html",
        title: "Profile - showybulgaria.com",
        resolve: {
          loginRequired: loginRequired
        }
      }).
      when("/logout", {
        controller: "LogoutController",
        templateUrl: false
      }).
      when("/about", {
        controller: "AboutController",
        templateUrl: "/partials/about.html",
        title: "About - showybulgaria.com"
      }).
      when("/hikes", {
        controller: "AllController",
        templateUrl: "/partials/all.html",
        title: "All Hikes - showybulgaria.com"
      }).
      when("/my-hikes", {
          controller: "AllController",
          templateUrl: "/partials/all.html",
          title: "My Hikes - showybulgaria.com",
          resolve: {
              loginRequired: loginRequired
          }
      }).
      when("/discover", {
        controller: "PhotoStreamController",
        templateUrl: "/partials/photo_stream.html",
        title: "Discover - showybulgaria.com"
      }).
      when("/map", {
        controller: "MapController",
        templateUrl: "/partials/map.html",
        reloadOnSearch: false,
        title: "Map - showybulgaria.com"
      }).
      when("/search", {
        controller: "SearchController",
        templateUrl: "/partials/search.html",
        title: "Search - showybulgaria.com"
      }).
      when("/admin", { // Any user can attempt to view this page, but the api layer will give no results unless they have admin credentials
        controller: "AdminController",
        templateUrl: "/partials/admin.html",
        title: "Admin - showybulgaria.com",
        resolve: {
          loginRequired: loginRequired
        }
      }).
      when("/hikes/:hikeId", {
        controller: "EntryController",
        templateUrl: "/partials/entry.html",
        resolve: {
          isEditing: function() { return false; }
        }
      }).
      when("/hikes/:hikeId/edit", {
        controller: "EntryController",
        templateUrl: "/partials/entry.html",
        resolve: {
          loginRequired: loginRequired,
          isEditing: function() { return true; }
        }
      })
      .otherwise({        
        redirectTo: '/'
      });
      //
      //Auth config
      $authProvider.httpInterceptor = true; // Add Authorization header to HTTP request
      $authProvider.loginOnSignup = true;
      $authProvider.baseUrl = '/'; // API Base URL for the paths below.
      $authProvider.loginRedirect = '/';
      $authProvider.logoutRedirect = '/';
      $authProvider.signupRedirect = '/login';
      $authProvider.loginUrl = '/auth/login';
      $authProvider.signupUrl = '/auth/signup';
      $authProvider.loginRoute = '/login';
      $authProvider.signupRoute = '/signup';
      $authProvider.tokenRoot = false; // set the token parent element if the token is not the JSON root
      $authProvider.tokenName = 'token';
      $authProvider.tokenPrefix = 'satellizer'; // Local Storage name prefix
      $authProvider.unlinkUrl = '/auth/unlink/';
      $authProvider.unlinkMethod = 'get';
      $authProvider.authHeader = 'Authorization';
      $authProvider.authToken = 'Bearer';
      $authProvider.withCredentials = true;
      $authProvider.platform = 'browser'; // or 'mobile'
      $authProvider.storage = 'localStorage'; // or 'sessionStorage'
      //
      $authProvider.facebook({
        clientId: '1620247318188885'
      });
      //1620247318188885
      $authProvider.google({
        clientId: '594854194818-j2mjr64k0l8iao75th2phf6ba47nde0d.apps.googleusercontent.com'
      });

      $authProvider.instagram({
        clientId: '40abf218971f41e4b6812a90c4e681fb'
      });
      // Removes the # in urls
      $locationProvider.html5Mode({
         enabled: true,
         requireBase: false
      });
  }])
  .run(["$http", "$location", "$rootScope", "$templateCache", "$timeout", "$window", "capabilities", "config", "navigation", "preferences", "resourceCache", 
    function($http, $location, $rootScope, $templateCache, $timeout, $window, capabilities, config, navigation, preferences, resourceCache) {
    // HACK, if url parameters include _escaped_fragment_ this request is being made by a crawler and the html is already rendered.
    // If angular starts to render again, things won't look right, so throw an exception to essentially disable angular
    if ($location.search()._escaped_fragment_ !== undefined) {
      throw new Error();
    }
    $rootScope.config = config;
    $rootScope.capabilities = capabilities;
    $rootScope.isProduction = $location.absUrl().indexOf("showybulgaria.com") > -1;
    $rootScope.location = $location;
    $rootScope.metaImage = config.landingPageImagesPath + "/the-narrows-thumb.jpg";
    $rootScope.Modernizr = Modernizr;
    $rootScope.navigation = navigation;
    $rootScope.preferences = preferences;
    var haveLoadedOnePage = false;
    $rootScope.$on("$routeChangeSuccess", function(event, current, previous) {
      $rootScope.metaCanonical = "http://showybulgaria.com" + ($location.path() === "/" ? ""  : $location.path());
      if (current && current.$$route && current.$$route.title) {
        $rootScope.title = current.$$route.title;
      }
      // Fix firefox issue where navigating between pages remembers the scroll position of previous page.
      if (haveLoadedOnePage) {
        $window.document.body.scrollTop = 0;
        $window.document.documentElement.scrollTop = 0;
      }
      haveLoadedOnePage = true;

    });
    $rootScope.$on("$locationChangeStart", function(event, next, current) {
      var isOnEntryEditPage = /\/hikes\/.*?\/edit/.test(next);
      if (isOnEntryEditPage && !capabilities.isEditPageSupported) {
        $window.alert("Sorry this browser doesn't support editing.");
        event.preventDefault();
      }
    });

    if(localStorage.getItem('currentUser') !== null) {
         $rootScope.userData = JSON.parse(localStorage.getItem('currentUser'));
     } else {
         $rootScope.userData = [];
     }

    // Pre-populate caches
    $timeout(function() {
      $http.get("/partials/entry.html",         { cache: $templateCache });
      $http.get("/partials/index.html",         { cache: $templateCache });      
      $http.get("/partials/photo_stream.html",  { cache: $templateCache });
      $http.get("/partials/map.html",           { cache: $templateCache });
      $http.get("/partials/search.html",        { cache: $templateCache });
      $http.get("/partials/login-page.html",    { cache: $templateCache });
      $http.get("/partials/profile.html",    { cache: $templateCache });
      // $http.get("/partials/signup.html",        { cache: $templateCache });
      $http.get("/api/v1/hikes?fields=distance,is_featured,locality,name,photo_facts,photo_landscape,photo_preview,string_id", { cache: resourceCache} );
//      $http.get("/data/index.json", { cache: resourceCache} );
    }, 1000);

    // IE 9 does its own caching, and requests are not hitting the server
    jQuery.ajaxSetup({ cache: false });
  }]);
