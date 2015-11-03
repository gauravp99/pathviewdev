/**
 * Created by ybhavnasi on 7/7/15.
 */

var app = angular.module('PathviewApp',[], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

app.controller('example2Controller',function($scope){
    $scope.GeneCompare = true;
    $scope.CpdCompare = true;
    console.log("hello from example2 controller");
    $scope.geneRefSelect = [1,3,5];
    $scope.cpdRefSelect = [1,2];
    $scope.geneSamSelect = [2,4,6];
    $scope.cpdSamSelect = [3,4];



});

app.controller('analysisController',function($scope,$timeout) {
    var outScope = $scope;
    $scope.GeneCompare = true;
    $scope.CpdCompare = true;
    $scope.showGeneContent = function($fileContent)
    {

        if($scope.filename)
            $scope.content = $fileContent;
        console.log("fileType: "+$scope.filetype);
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
            $scope.Genecolumns = $fileContent.split("\n")[0].split("\t");
            var columns1 = $fileContent.split("\n")[0].split("\t");
            var columns2 = $fileContent.split("\n")[1].split("\t");

            if(columns1.length === columns2.length)
            {
                $scope.Genecolumns.splice(0,1);
            }
            $scope.geneColumns = columns1.length;


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


        $('#edit').css("visibility", "");
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
            $scope.Compoundcolumns = $fileContent.split("\n")[0].split("\t");
            var columns1 = $fileContent.split("\n")[0].split("\t");
            var columns2 = $fileContent.split("\n")[1].split("\t");

            if(columns1.length === columns2.length)
            {
                $scope.Compoundcolumns.splice(0,1);
            }
            $scope.cpdColumns = columns1.length;


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
        $('#cpdedit').css("visibility", "");
        $('option[disabled="false"]').removeAttr('disabled');

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

                if(attrs['onReadFile'].substring(0,10) === "showGeneCo")
                {
                    console.log("genes");
                    scope.geneRefSelect = "";
                    scope.geneSamSelect = "";

                }
                else{
                    console.log("else compound");
                    scope.cpdRefSelect = "";
                    scope.cpdSamSelect = "";

                }

                reader.readAsText((onChangeEvent.srcElement || onChangeEvent.target).files[0]);

            }) ;

        }
    };
});


