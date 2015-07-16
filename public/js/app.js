/**
 * Created by ybhavnasi on 7/7/15.
 */

var app = angular.module('GageApp',[], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});
app.controller('analysisController',function($scope) {





    $scope.showContent = function($fileContent)
    {
        console.log("data uploader");
        console.log($scope.uploader);

        if($scope.filename)
            $scope.content = $fileContent;


        if($scope.filetype === 'text/csv')
        {
            $scope.content = $fileContent.split("\n")[0].replace(" ", "").split(",").length;
            $scope.columns = $fileContent.split("\n")[0].replace(" ", "").split(",");
            $scope.columns.splice($scope.columns[0], 1);
            console.log($scope.filetype+" csv detected");
        }
        else if($scope.filetype === 'text/plain')
        {
            /* String.prototype.countWords = function(){
             return this.split(/\s+/).length;
             }
             $scope.content = $fileContent.split("\n")[0].countWords();*/
            $scope.columns = $fileContent.split("\n")[0].replace(/\s/g,",").split(",");
            $scope.columns.splice($scope.columns[0], 1);
            console.log($scope.columns.length);
            $scope.sample=[];
            $scope.ref=[];


            console.log($scope);
            console.log($scope.filetype+" text detected");
        }
        else
        {
            console.log("not in specified format");
        }
        $scope.content = $fileContent.split("\n")[0];

    };
    console.log("In analysis Page");
});

app.directive('onReadFile', function($parse){
    return {
        restrict: 'A',
        scope: false,
        link: function(scope, element, attrs) {
            var fn = $parse(attrs.onReadFile);
            element.on('change',function(onChangeEvent){
                var reader = new FileReader();


                reader.onload = function(onLoadEvent) {
                    scope.$apply(function(){
                        fn(scope,{$fileContent:onLoadEvent.target.result});
                    });
                };
                scope.filetype =( (onChangeEvent.srcElement || onChangeEvent.target).files[0].type);
                reader.readAsText((onChangeEvent.srcElement || onChangeEvent.target).files[0]);

            }) ;

        }
    };
});

