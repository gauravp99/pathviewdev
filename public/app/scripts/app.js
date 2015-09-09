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
  .module('mytodoApp', ['ngRoute','commentCtrl', 'commentService','angularUtils.directives.dirPagination'])
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
        templateUrl: 'views/profile.html',
        controller: 'ProfileCtrl'
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
  });
  /*.run(function(Authentication, Application, $rootScope, $location, RouteFilter){

    Authentication.requestUser().then(function()
      {
        console.log("before making the application ready");
        Application.makeReady();
      });

    $rootScope.$on('$locationChangeStart',function(scope,next,current){

        if($location.path() === '/loading')
        {
        return;
        }
      if(! Application.isReady())
      {
        $location.path('loading');
      }
     /!* RouteFilter.run($location.path());*!/
    })

  });*/
var underscore = angular.module('underscore', []);
underscore.factory('_', function() {
  return window._; // assumes underscore has already been loaded on the page
});