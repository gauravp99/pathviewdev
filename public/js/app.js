/**
 * Created by ybhavnasi on 7/7/15.
 */

var app = angular.module('GageApp',[], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});
app.controller('analysisController',function($scope,$timeout) {
    var outScope = $scope;
    $scope.delete = function(){
        console.log("in delete function");
        console.log(sampleselect);
        $('.tempColumn').remove();
        console.log(sampleselect);
    };

    $scope.showContent = function($fileContent)
    {

        console.log("displaying the sample and reference columns");
        console.log($scope.sampleselect);
        console.log($scope.refselect);
        if($scope.filename)
            $scope.content = $fileContent;


        if($scope.filetype === 'text/csv')
        {
            $scope.content = $fileContent.split("\n")[0].replace(" ", "").split(",").length;
            $scope.columns = $fileContent.split("\n")[0].replace(" ", "").split(",");
            $scope.columns.splice($scope.columns[0], 1);

        }
        else if($scope.filetype === 'text/plain')
        {
            $scope.columns = $fileContent.split("\n")[0].replace(/\s/g,",").split(",");
            $scope.columns.splice($scope.columns[0], 1);
            $scope.sample=[];
            $scope.ref=[];

        }
        else
        {
            alert("File uploded is not in specified format");

        }
        $scope.content = $fileContent.split("\n")[0];


    };
    console.log("In analysis Page");
});


app.controller('ExampleAnalysisController1',function($scope) {
    $scope.columns = ['HN_1','DCIS_1','HN_2','DCIS_2','HN_3','DCIS_3','HN_4','DCIS_4','HN_5','DCIS_5','HN_6','DCIS_6'];
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

