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
       console.log("name: "+$scope.name);
       console.log("email: "+$scope.email);
       console.log("organization: "+$scope.organization);
       var data = {};
       data = {
         name: $scope.name,
         email: $scope.email,
         organisation: $scope.organization
       };
       console.log(data);
         $scope.created = false;
         $scope.existed = false;
       $http.post('api/addUser',data)
        .then(function(response){
               if(response.data == "createdUser")
               {
                   $scope.created = true;
               }

               else if(response.data == "userAlreadyExist")
               {
                   $scope.existed = true;
               }
               $('#successful').trigger('click');
            console.log(response);
               console.log($scope.created);
               console.log($scope.existed);
        },function(response){
             console.error("error");
        });

     };








  });
