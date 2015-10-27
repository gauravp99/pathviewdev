'use strict';

/**
 * @ngdoc function
 * @name mytodoApp.controller:ProfileCtrl
 * @description
 * # ProfileCtrl
 * Controller of the mytodoApp
 */
angular.module('mytodoApp')
  .controller('UserCreationController', function ($scope,$rootScope,$http) {

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
         $rootScope.created = false;
         $rootScope.existed = false;
       $http.post('/api/addUser',data)
        .then(function(response){
               if(response.data == "createdUser")
               {
                   $rootScope.created = true;
               }

               else if(response.data == "userAlreadyExist")
               {
                   $rootScope.existed = true;
               }
               $('#successful').trigger('click');
            console.log(response);
               console.log($rootScope.created);
               console.log($rootScope.existed);
        },function(response){
             console.error("error");
        });

     };








  });
