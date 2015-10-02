/**
 * Created by ybhavnasi on 7/7/15.
 */

var app = angular.module('PathviewApp',[], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});
app.controller('analysisController',function($scope,$timeout) {
    var outScope = $scope;
    $scope.GeneCompare = "paired";
    $scope.CpdCompare = "paired";
    $scope.showGeneContent = function($fileContent)
    {
        if($scope.filename)
            $scope.content = $fileContent;

        if($scope.filetype === 'text/csv')
        {
            $scope.content = $fileContent.split("\n")[0].replace(" ", "").split(",").length;
            $scope.Genecolumns = $fileContent.split("\n")[0].replace(" ", "").split(",");
            var columns1 = $fileContent.split("\n")[0].replace(/\s/g,",").split(",");
            var columns2 = $fileContent.split("\n")[1].replace(/\s/g,",").split(",");
            console.log(columns1.length+" "+columns2.length);
            if(columns1.length === columns2.length)
            {
                $scope.Genecolumns.splice(0, 1);
            }

        }
        else if($scope.filetype === 'text/plain')
        {
            $scope.Genecolumns = $fileContent.split("\n")[0].replace(/\s/g,",").split(",");
            var columns1 = $fileContent.split("\n")[0].replace(/\s/g,",").split(",");
            var columns2 = $fileContent.split("\n")[1].replace(/\s/g,",").split(",");

            if(columns1.length === columns2.length)
            {
                $scope.Genecolumns.splice(0,1);
            }
            $scope.sample=[];
            $scope.ref=[];

        }
        else
        {
            alert("File uploded is not in specified format");
            $('#gfilePopUp').trigger('click');
        }


        $scope.content = $fileContent.split("\n")[0];
        console.log($scope);


        if( $scope.Genecolumns.length > 0)
        {

        }
        else{
            $('#gfilePopUp').trigger('click');
        }


        $('#geneMenu').css("visibility", "");
    };
    $scope.showCompoundContent = function($fileContent)
    {
        if($scope.filename)
            $scope.content = $fileContent;

        if($scope.filetype === 'text/csv')
        {
            $scope.content = $fileContent.split("\n")[0].replace(" ", "").split(",").length;
            $scope.Compoundcolumns = $fileContent.split("\n")[0].replace(" ", "").split(",");
            var columns1 = $fileContent.split("\n")[0].replace(/\s/g,",").split(",");
            var columns2 = $fileContent.split("\n")[1].replace(/\s/g,",").split(",");
            console.log(columns1.length+" "+columns2.length);
            if(columns1.length === columns2.length)
            {
                $scope.Compoundcolumns.splice(0, 1);
            }

        }
        else if($scope.filetype === 'text/plain')
        {
            $scope.Compoundcolumns = $fileContent.split("\n")[0].replace(/\s/g,",").split(",");
            var columns1 = $fileContent.split("\n")[0].replace(/\s/g,",").split(",");
            var columns2 = $fileContent.split("\n")[1].replace(/\s/g,",").split(",");

            if(columns1.length === columns2.length)
            {
                $scope.Compoundcolumns.splice(0,1);
            }
            $scope.sample=[];
            $scope.ref=[];

        }
        else
        {
            alert("File uploded is not in specified format");
            $('#gfilePopUp').trigger('click');
        }


        $scope.content = $fileContent.split("\n")[0];
        console.log($scope);


        if( $scope.Compoundcolumns.length > 0)
        {

        }
        else{
            $('#gfilePopUp').trigger('click');
        }


        $('#compoundMenu').css("visibility", "");
    };


    if ($scope.columns == null)
    {
        $('#gfilePopUp').trigger('click');
    }
    else{
            $('#compoundMenu').css("visibility", "");
        $('#geneMenu').css("visibility", "");
    }






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

