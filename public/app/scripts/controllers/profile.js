'use strict';

/**
 * @ngdoc function
 * @name mytodoApp.controller:ProfileCtrl
 * @description
 * # ProfileCtrl
 * Controller of the mytodoApp
 */
angular.module('mytodoApp')
  .controller('UserCreationController', function ($scope,$http) {

     $scope.addUser = function(){
       console.log("name:"+$scope.name);
       console.log("email:"+$scope.emailAddress);
       console.log("organization:"+$scope.organization);
       var data = {};
       data = {
         name: $scope.name,
         email: $scope.emailAddress,
         organisation: $scope.organization
       };
       console.log(data);
       $http.post('api/addUser',data)
        .then(function(response){
            console.log(response);
        },function(response){
             console.error("error");
        });

     };








  });
