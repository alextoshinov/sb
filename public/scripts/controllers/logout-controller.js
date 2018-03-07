"use strict";
angular.module("showyBulgariaApp")
    .controller('LogoutController', ['$rootScope', '$window', '$location', '$auth', 'toastr',
    function($rootScope, $window, $location, $auth, toastr) {
        if (!$auth.isAuthenticated()) { return; }
        $auth.logout()
            .then(function() {
                delete $window.localStorage.currentUser;
                $rootScope.userData = [];
                toastr.info('You have been logged out');
                // $location.path('/');
            });
    }]);