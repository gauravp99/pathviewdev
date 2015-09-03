'use strict';

/**
 * @ngdoc function
 * @name mytodoApp.controller:SettingsCtrl
 * @description
 * # SettingsCtrl
 * Controller of the mytodoApp
 */
angular.module('mytodoApp')
  .controller('SettingsCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
