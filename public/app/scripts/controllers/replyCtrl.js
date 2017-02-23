angular.module('mytodoApp')

.controller('replyCtrl', function($scope, $http, CommentReply){

        $scope.commentReplyData = {};
        $scope.loading = true;
        CommentReply.get()
            .success(function(data){
               $scope.commentReply = data;
                $scope.loading = false;
            });
        $scope.submitCommentReply = function() {
            $scope.loading = true;
            CommentReply.save($scope.commentReplyData)
                .success(function(getData){
                    CommentReply.get()
                        .success(function (getData) {
                            $scope.commentReply = getData;
                            $scope.loading = false;
                        });
                })
                .error(function(data){
			console.log('-error function');
                });
        };
        $scope.deleteCommentReply = function(id) {
            $scope.loading = true;
            CommentReply.destroy(id)
                .success(function (getData) {
                    CommentReply.get()
                        .success(function (getData) {
                            $scope.commentReply = getData;
                            $scope.loading = false;
                        });
                });
        };
    }).factory('CommentReply', function($http){
        return {
            get: function () {
                return $http.get('/api/commentReply');
            },
            save: function (commentReplyData) {
                return $http({
                    method: 'POST',
                    url: '/api/commentReply',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: $.param(commentReplyData)
                });
            },
            destroy: function (id) {
                return $http.delete('/api/commentReply/' + id);
            }

        }
    });
