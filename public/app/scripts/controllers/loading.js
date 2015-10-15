'use strict';

/**
 * @ngdoc function
 * @name mytodoApp.controller:LoadingCtrl
 * @description
 * # LoadingCtrl
 * Controller of the mytodoApp
 */
angular.module('mytodoApp')
  .controller('LoadingCtrl', function ($scope, Application, $location) {

  	Application.registerListener(function()
  	{
  		console.log("in register Listener function");
  		$location.path("/")

  	});
  
  });
