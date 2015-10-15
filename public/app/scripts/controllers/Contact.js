/**
 * Created by yeshvant on 10/4/15.
 */
angular.module('mytodoApp')
    .controller('contactCtrl', function ($scope,$http) {
        $http.get('/api/getAllUsers').
            then(function(response) {
                $scope.emailList = "";
                $.each(response.data, function(value, key) {
                    $scope.emailList +=key.email+";";
                });

            }, function(response) {
                alert("Alert!! Unable to get details from server.")
            });

        $scope.submit = function () {
            console.log("hello in submit function");
            $http({
                url: '/api/sendMessage',
                method: "POST",
                data: {'emailList': $scope.emailList, 'subject': $scope.subject, 'message': $scope.message}
            })
                .then(function (response) {
                    console.log(response.data);
                    $('#successful').trigger('click');
                    console.log("success");
                },
                function (response) { // optional
                    console.log("failure" + response);
                });
        };

        console.log($scope);

    });