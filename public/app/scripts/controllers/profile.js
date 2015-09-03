'use strict';

/**
 * @ngdoc function
 * @name mytodoApp.controller:ProfileCtrl
 * @description
 * # ProfileCtrl
 * Controller of the mytodoApp
 */
angular.module('mytodoApp')
  .controller('ProfileCtrl', function ($scope) {
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
