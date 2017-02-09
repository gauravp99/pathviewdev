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
                .success(function(data){
                    CommentReply.get()
                        .success(function(getData){
                            $scope.commentReplies = getData;
                            $scope.loading = false;
                        });
                })
                .error(function(data){
                   console.log(data);
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
                    data: commentReplyData
                });
            },
            destroy: function (id) {
                return $http.delete('/api/commentReply/' + id);
            }

        }
    });
