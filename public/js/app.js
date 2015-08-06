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
        $('.tempColumn').remove();
    };

    $scope.showContent = function($fileContent)
    {
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
        var geneIDArray = [];
        $.each($fileContent.split("\n"),function($index,$line){
            geneIDArray.push($line.split("\t")[0]);

        });

       /* var duplication = _.chain(geneIDArray).countBy().pairs().max(_.last).value();
        console.log(duplication);
        if(duplication[1] > 2)
        {
            alert("File Contains duplication rows most duplicated column is a \"" + duplication[0]+"\"" );
        }*/
        $scope.content = $fileContent.split("\n")[0];



    };

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

