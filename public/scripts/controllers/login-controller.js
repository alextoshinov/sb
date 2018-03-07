"use strict";
angular.module("showyBulgariaApp")
  .controller('LoginController',['$scope', '$rootScope', '$window', '$location', '$auth', '$route' ,'toastr',
          function($scope, $rootScope, $window, $location, $auth,$route, toastr) {

              $scope.login = function() {
                  $auth.login($scope.user)
                      .then(function(response) {
                          $window.localStorage.currentUser = JSON.stringify(response.data);
                          $rootScope.userData = response.data;
                          // $rootScope.$emit("CallNotifications", {});
                          toastr.success('You have successfully signed in');
                          $window.location.href = '/';
                          $location.path('/');
                      })
                      .catch(function(response) {
                          toastr.error(response.data.message, response.status);
                      });
              };
              //
              $scope.authenticate = function(provider) {
                  $auth.authenticate(provider)
                      .then(function(response) {
                          $window.localStorage.currentUser = JSON.stringify(response.data);
                          $rootScope.userData = response.data;
                          // $rootScope.$emit("CallNotifications", {});
                          toastr.success('You have successfully signed in with ' + provider + '!');
                          $window.location.href = '/';
                          $location.path('/');

                      })
                      .catch(function(error) {
                          if (error.error) {
                              // Popup error - invalid redirect_uri, pressed cancel button, etc.
                              toastr.error(error.error);
                          } else if (error.data) {
                              // HTTP response error from server
                              toastr.error(error.data.message, error.status);
                          } else {
                              toastr.error(error);
                          }
                          console.log(provider + ' ERROR: ',error);
                      });
              };
              //
          }]);