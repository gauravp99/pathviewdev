/**
 * Created by ybhavnasi on 10/12/15.
 */

var module = angular.module('faqApp',[],function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');

});
module.controller('faqController', function($scope, $http, Comment){

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

    });

module.factory('Comment', function($http){

    return {
        get: function () {
            return $http.get('/api/comments');
        },
        save: function (commentData) {
            return $http({
                method: 'POST',
                url: '/api/comments',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param(commentData)
            });
        },

        destroy: function (id) {
            return $http.delete('/api/comments/' + id);
        }

    }


});