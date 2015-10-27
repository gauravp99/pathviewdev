'use strict';

/**
 * @ngdoc function
 * @name mytodoApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the mytodoApp
 */
angular.module('mytodoApp')
  .controller('MainCtrl', function ($scope, $location,$rootScope,AUTH_EVENTS,AuthService) {

        $scope.getClass = function (path) {
            if ($location.path().substr(0, path.length) === path) {
                return 'active';
            } else {
                return '';
            }
        };

        $scope.credentials = {
            email: '',
            password: ''
        };

        $scope.login = function (credentials) {
            AuthService.login(credentials).then(function (user) {
                $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                $scope.setCurrentUser(user);
                console.log(user);
                if(user != null)
                {
                    $('#login').hide();
                }else{
                    console.log("in elase");
                }
            }, function () {

                $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
            });
        };




  });
