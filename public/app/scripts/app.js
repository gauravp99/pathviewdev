'use strict';

/**
 * @ngdoc overview
 * @name mytodoApp
 * @description
 * # mytodoApp
 *
 * Main module of the application.
 */
angular
  .module('mytodoApp', ['ngRoute','commentCtrl', 'commentService',])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
        .when('/comment', {
          templateUrl: 'views/comments.html',
          controller: 'mainController'
        })
        .when('/faq', {
          templateUrl: 'views/faq.html',
          controller: 'faqController'
        })
      .when('/about', {
        templateUrl: 'views/about.html',
        controller: 'aboutCtrl'
      })
        .when('/trends', {
          templateUrl: 'views/AnalysisTrends.html',
          controller: 'trendsCtrl'
        })
        .when('/trends-monthly', {
          templateUrl: 'views/AnalysisTrendsMonthly.html',
          controller: 'MonthlyTrendsCtrl'
        })
        .when('/trends-mannual', {
          templateUrl: 'views/AnalysisTrendsMannual.html',
          controller: 'ManualTrendsCtrl'
        })
      .when('/contact', {
        templateUrl: 'views/contact.html',
        controller: 'contactCtrl'
      })
      .when('/loading', {
        templateUrl: 'views/loading.html',
        controller: 'LoadingCtrl'
      })
      .when('/RouteFilter', {
        templateUrl: 'views/routefilter.html',
        controller: 'RoutefilterCtrl'
      })
      .when('/profile', {
        templateUrl: 'views/addMultipleUsers.html',
        controller: 'UserCreationController'
      })
      .when('/settings', {
        templateUrl: 'views/settings.html',
        controller: 'SettingsCtrl'
      })
      .when('/signin', {
        templateUrl: 'views/signin.html',
        controller: 'SigninCtrl'
      })

      .otherwise({
        redirectTo: '/'
      });
  })

.constant('AUTH_EVENTS', {
  loginSuccess: 'auth-login-success',
  loginFailed: 'auth-login-failed',
  logoutSuccess: 'auth-logout-success',
  sessionTimeout: 'auth-session-timeout',
  notAuthenticated: 'auth-not-authenticated',
  notAuthorized: 'auth-not-authorized'
})
    .constant('USER_ROLES', {
      all: '*',
      admin: 'admin',
      editor: 'editor',
      guest: 'guest'
    })
.factory('AuthService', function ($http, Session) {
  var authService = {};

  authService.login = function (credentials) {

    return $http
        .post('/api/authenticate', credentials)
        .then(function (res) {

          Session.create(res.data.id, res.data.user,res.data.role);
          return {'id':res.data.user,'role':res.data.role};
        });
  };

  authService.isAuthenticated = function () {
    return !!Session.userId;
  };

  authService.isAuthorized = function (authorizedRoles) {
    if (!angular.isArray(authorizedRoles)) {
      authorizedRoles = [authorizedRoles];
    }
    return (authService.isAuthenticated() &&
    authorizedRoles.indexOf(Session.userRole) !== -1);
  };

  return authService;
})
.service('Session', function () {
      this.create = function (sessionId, userId,userrole) {
        this.id = sessionId;
        this.userId = userId;
        this.userRole =userrole;
      };
      this.destroy = function () {
        this.id = null;
        this.userId = null;
        this.userRole = null;
      };
    })
    .directive('loginDialog', function (AUTH_EVENTS) {
      return {
        restrict: 'A',
        template: '<div ng-if="visible" ng-include="\'../views/main.html\'">',
        link: function (scope) {
          var showDialog = function () {
            scope.visible = true;
          };

          scope.visible = false;
          scope.$on(AUTH_EVENTS.notAuthenticated, showDialog);
          scope.$on(AUTH_EVENTS.sessionTimeout, showDialog)
        }
      };
    })
.controller('ApplicationController', function ($scope,
                                                   USER_ROLES,
                                                   AuthService) {
      $scope.currentUser = null;
      $scope.userRoles = USER_ROLES;
      $scope.isAuthorized = AuthService.isAuthorized;

      $scope.setCurrentUser = function (user) {
        $scope.currentUser = user;
      };
    });

var underscore = angular.module('underscore', []);
underscore.factory('_', function() {
  return window._; // assumes underscore has already been loaded on the page
});