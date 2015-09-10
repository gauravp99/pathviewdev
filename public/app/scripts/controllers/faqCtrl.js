angular
    .module('faq', ['ngRoute','commentCtrl', 'commentService','angularUtils.directives.dirPagination'])
     .config(function ($routeProvider) {
    $routeProvider
        .when('/faq', {
            templateUrl: 'views/faq.html',
            controller: 'faqController'
        })});


angular.module('commentCtrl',[])

    .controller('faqController', function($scope, $http, Comment){

        $scope.commentData = {};

        $scope.loading = true;

        Comment.get()
            .success(function(data){
                $scope.comments = data;
                $scope.loading = false;
            });

        $scope.submitComment = function() {
            $scope.loading = true;
            Comment.save($scope.commentData)
                .success(function(data){
                    Comment.get()
                        .success(function(getData){
                            $scope.comments = getData;
                            $scope.loading = false;
                        });
                })
                .error(function(data){
                    console.log(data);
                });
        };

        $scope.deleteComment = function(id) {
            $scope.loading = true;

            Comment.destroy(id)
                .success(function (getData) {
                    Comment.get()
                        .success(function (getData) {
                            $scope.comments = getData;
                            $scope.loading = false;
                        });
                });
        };
        $scope.showReply = function(id){
            $('#'+id).show();
        };

        $scope.hideReply = function(id){
            $('#'+id).hide();
        };

    });/**
 * Created by ybhavnasi on 9/10/15.
 */
