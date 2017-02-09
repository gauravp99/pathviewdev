angular.module('commentReplyService',[])
.factory('CommentReply', function($http){

        return {
            get: function () {
                return $http.get('/api/commentReply');
            }

        }
    });
